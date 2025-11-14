<?php

namespace App\Jobs;

use App\Models\AnalyticsEvent;
use App\Models\AnalyticsMetric;
use App\Models\Project;
use App\Models\StorageSetting;
use App\Models\StorageUsageLog;
use App\Services\Storage\Data\AssetUploadData;
use App\Services\Storage\Data\StoredAssetPaths;
use App\Services\Storage\ProjectAssetManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessProjectAssets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $projectId,
        public readonly array $assetPayload,
        public readonly string $storageType,
        public readonly array $previousPaths = [],
        public readonly ?string $previousStorageType = null
    ) {
        $settings = StorageSetting::getSettings();
        $this->onConnection($settings->queue_connection ?? 'database');
    }

    /**
     * Execute the job.
     */
    public function handle(ProjectAssetManager $assetManager): void
    {
        try {
            $project = Project::findOrFail($this->projectId);
            
            // Update status to processing
            $project->update([
                'processing_status' => 'processing',
                'processing_error' => null,
            ]);

            // Ensure S3 configuration is applied before processing
            $settings = StorageSetting::getSettings();
            $settings->applyToConfig();
            
            // Clear any cached config to ensure fresh values
            config()->set('filesystems.disks.project_s3', null);

            $payload = AssetUploadData::fromArray($this->assetPayload);

            $stored = $assetManager->store($project, $payload, $this->storageType);

            if (!empty($this->previousPaths)) {
                $assetManager->delete(
                    $project,
                    StoredAssetPaths::fromArray($this->previousPaths),
                    $this->previousStorageType ?? $this->storageType
                );
            }

            $project->update(array_merge(
                $stored->toArray(),
                [
                    'storage_type' => $this->storageType,
                    'processing_status' => 'completed',
                    'processing_job_id' => null,
                    'processing_error' => null,
                ]
            ));

            $this->logUsage($project, $stored);
            $this->recordAnalytics($project, $stored);

            $this->cleanupTempFiles($payload);

            Cache::forget('s3-usage-snapshot-' . now()->format('Ym'));
        } catch (\Throwable $e) {
            // Update project status to failed
            try {
                $project = Project::find($this->projectId);
                if ($project) {
                    $project->update([
                        'processing_status' => 'failed',
                        'processing_error' => $e->getMessage(),
                    ]);
                }
            } catch (\Throwable $updateError) {
                // Ignore update errors
            }

            Log::error("Error processing project assets", [
                'project_id' => $this->projectId,
                'storage_type' => $this->storageType,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Project asset processing failed', [
            'project_id' => $this->projectId,
            'error' => $exception->getMessage(),
        ]);

        // Update project status to failed
        try {
            $project = Project::find($this->projectId);
            if ($project) {
                $project->update([
                    'processing_status' => 'failed',
                    'processing_error' => $exception->getMessage(),
                ]);
            }
        } catch (\Throwable $updateError) {
            // Ignore update errors
        }

        StorageUsageLog::create([
            'project_id' => $this->projectId,
            'storage_type' => $this->storageType,
            'action' => 'upload',
            'status' => 'failed',
            'message' => $exception->getMessage(),
        ]);
    }

    protected function logUsage(Project $project, StoredAssetPaths $paths): void
    {
        $disk = $this->storageType === 's3' ? 'project_s3' : 'project_local';

        foreach ($paths->toArray() as $type => $path) {
            if (!$path) {
                continue;
            }

            $bytes = 0;
            try {
                $bytes = \Illuminate\Support\Facades\Storage::disk($disk)->size($path);
            } catch (\Throwable $e) {
                // ignore size retrieval failure
            }

            $message = ucfirst(str_replace('_file', '', $type)) . ' uploaded';
            $requestType = $this->storageType === 's3' ? 'PUT' : null;

            StorageUsageLog::create([
                'project_id' => $project->id,
                'storage_type' => $this->storageType,
                'action' => 'upload',
                'request_type' => $requestType,
                'path' => $path,
                'bytes' => $bytes,
                'status' => 'success',
                'message' => $message,
            ]);
        }
    }

    protected function recordAnalytics(Project $project, StoredAssetPaths $paths): void
    {
        $disk = $this->storageType === 's3' ? 'project_s3' : 'project_local';

        $metrics = AnalyticsMetric::today();

        foreach (['thumbnail', 'image', 'source_file', 'video'] as $key) {
            $pathKey = $key === 'source_file' ? 'source' : ($key === 'video' ? 'video' : $key);
            $path = $paths->{$pathKey} ?? null;
            if (!$path) {
                continue;
            }

            $bytes = 0;
            try {
                $bytes = \Illuminate\Support\Facades\Storage::disk($disk)->size($path);
            } catch (\Throwable $e) {
                // ignore
            }

            AnalyticsEvent::create([
                'event_type' => 'ingress',
                'context' => $key,
                'project_id' => $project->id,
                'bytes' => $bytes,
                'meta' => [
                    'storage_type' => $this->storageType,
                    'path' => $path,
                ],
                'occurred_at' => now(),
            ]);

            if ($this->storageType === 's3') {
                $metrics->increment('bandwidth_s3_bytes', $bytes);
                $metrics->increment('s3_put_requests');
            } else {
                $metrics->increment('bandwidth_local_bytes', $bytes);
            }
        }
    }

    protected function cleanupTempFiles(AssetUploadData $payload): void
    {
        foreach ([
            $payload->thumbnailTempPath,
            $payload->imageTempPath,
            $payload->sourceTempPath,
            $payload->videoTempPath,
        ] as $tempPath) {
            if ($tempPath && file_exists(storage_path('app/' . ltrim($tempPath, '/')))) {
                @unlink(storage_path('app/' . ltrim($tempPath, '/')));
            }
        }
    }
}

