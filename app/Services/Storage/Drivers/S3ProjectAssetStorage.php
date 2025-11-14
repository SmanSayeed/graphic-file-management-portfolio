<?php

namespace App\Services\Storage\Drivers;

use App\Models\Project;
use App\Models\StorageSetting;
use App\Services\Storage\Contracts\ProjectAssetStorage;
use App\Services\Storage\Data\AssetUploadData;
use App\Services\Storage\Data\StoredAssetPaths;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3ProjectAssetStorage implements ProjectAssetStorage
{
    protected string $disk = 'project_s3';

    public function store(Project $project, AssetUploadData $payload): StoredAssetPaths
    {
        $paths = new StoredAssetPaths();

        if ($payload->thumbnailTempPath) {
            $paths->thumbnail = $this->storeFile($payload->thumbnailTempPath, $payload->thumbnailOriginalName, 'thumbnails');
        }

        if ($payload->imageTempPath) {
            $paths->image = $this->storeFile($payload->imageTempPath, $payload->imageOriginalName, 'images');
        }

        if ($payload->sourceTempPath) {
            $paths->source = $this->storeFile($payload->sourceTempPath, $payload->sourceOriginalName, 'sources');
        }

        if ($payload->videoTempPath) {
            $paths->video = $this->storeFile($payload->videoTempPath, $payload->videoOriginalName, 'videos');
        }

        return $paths;
    }

    public function delete(Project $project, StoredAssetPaths $paths): void
    {
        $disk = $this->getDisk();

        foreach (['thumbnail', 'image', 'source', 'video'] as $attribute) {
            $path = $paths->{$attribute};
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    public function url(string $path): string
    {
        $disk = $this->getDisk();

        // Try to get a temporary URL first (works even with Block Public Access)
        // Temporary URLs are valid for 24 hours
        try {
            return $disk->temporaryUrl($path, now()->addHours(24));
        } catch (\Throwable $e) {
            // If temporary URL fails, try regular URL (works if public access is enabled)
            try {
                return $disk->url($path);
            } catch (\Throwable $e2) {
                // If both fail, log and return empty string
                Log::error("Failed to generate URL for S3 file: {$path} - " . $e2->getMessage());
                return '';
            }
        }
    }

    /**
     * Get the S3 disk with fresh configuration
     * This ensures credentials are always up-to-date
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function getDisk()
    {
        $settings = StorageSetting::getSettings();
        $settings->applyToConfig();

        $config = config('filesystems.disks.project_s3');

        if (!$config || !isset($config['key']) || !isset($config['secret'])) {
            throw new \RuntimeException('S3 configuration is missing or incomplete. Please check storage settings.');
        }

        $config['throw'] = true;

        try {
            return Storage::build($config);
        } catch (\Exception $e) {
            Log::error("Failed to build S3 disk instance", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function storeFile(string $tempPath, ?string $originalName, string $type): string
    {
        $disk = $this->getDisk();
        $tempDisk = Storage::disk('local');
        $directory = $this->resolveDirectory($type);
        $filename = $this->generateFilename($originalName);

        if (!$tempDisk->exists($tempPath)) {
            throw new \RuntimeException("Temporary file [{$tempPath}] does not exist.");
        }

        $contents = $tempDisk->get($tempPath);
        if ($contents === false || $contents === null) {
            throw new \RuntimeException("Unable to read temporary file [{$tempPath}] from local disk.");
        }

        $path = "{$directory}/{$filename}";

        try {
            // Upload without ACL to work with Block Public Access enabled
            $result = $disk->put($path, $contents);

            if ($result === false) {
                throw new \RuntimeException("S3 upload failed: put() returned false");
            }

            // Verify file exists on S3
            if (!$disk->exists($path)) {
                throw new \RuntimeException("File upload appeared to succeed but file does not exist on S3: {$path}");
            }

            return $path;
        } catch (\Exception $e) {
            Log::error("Failed to upload file to S3", [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw new \RuntimeException("Failed to upload file to S3: " . $e->getMessage(), 0, $e);
        }
    }

    protected function generateFilename(?string $originalName): string
    {
        $extension = $originalName ? pathinfo($originalName, PATHINFO_EXTENSION) : 'dat';
        return Str::uuid() . ($extension ? ".{$extension}" : '');
    }

    protected function resolveDirectory(string $type): string
    {
        $settings = StorageSetting::getSettings();
        $base = trim($settings->s3_prefix ?? '', '/');

        if ($base === '') {
            return "projects/{$type}";
        }

        return "{$base}/projects/{$type}";
    }
}
