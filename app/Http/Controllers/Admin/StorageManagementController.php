<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QueueRunLog;
use App\Models\StorageSetting;
use App\Models\StorageUsageLog;
use App\Services\Storage\S3UsageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StorageManagementController extends Controller
{
    public function index(S3UsageService $usageService)
    {
        $settings = StorageSetting::getSettings();
        $settings->applyToConfig();

        $usageSnapshot = $usageService->snapshot();
        $recentUsage = StorageUsageLog::with('project')
            ->latest()
            ->limit(10)
            ->get();
        $queueLogs = QueueRunLog::latest()->limit(10)->get();

        $queueStats = [
            'pending' => DB::table('jobs')->count(),
            'failed' => DB::table('failed_jobs')->count(),
        ];

        $failedJobs = DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->limit(5)
            ->get();

        return view('admin.storage.index', compact(
            'settings',
            'usageSnapshot',
            'recentUsage',
            'queueLogs',
            'queueStats',
            'failedJobs'
        ));
    }

    public function updateSettings(Request $request): JsonResponse|RedirectResponse
    {
        $settings = StorageSetting::getSettings();

        $validated = $request->validate([
            'default_storage_type' => ['required', Rule::in(['local', 's3'])],
            'avoid_s3' => ['sometimes', 'boolean'],
            's3_access_key' => ['nullable', 'string'],
            's3_secret_key' => ['nullable', 'string'],
            's3_region' => ['nullable', 'string'],
            's3_bucket' => ['nullable', 'string'],
            's3_prefix' => ['nullable', 'string'],
            's3_endpoint' => ['nullable', 'url'],
            's3_use_path_style_endpoint' => ['sometimes', 'boolean'],
            's3_enable_usage_guard' => ['sometimes', 'boolean'],
            'queue_connection' => ['required', Rule::in(['database', 'sync'])],
            'queue_max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'queue_backoff' => ['required', 'integer', 'min:0', 'max:120'],
            'analytics_enabled' => ['sometimes', 'boolean'],
        ]);

        $settings->fill([
            'default_storage_type' => $validated['default_storage_type'],
            'avoid_s3' => $request->boolean('avoid_s3'),
            's3_access_key' => $validated['s3_access_key'] ?? null,
            's3_secret_key' => $validated['s3_secret_key'] ?? null,
            's3_region' => $validated['s3_region'] ?? null,
            's3_bucket' => $validated['s3_bucket'] ?? null,
            's3_prefix' => $validated['s3_prefix'] ?? null,
            's3_endpoint' => $validated['s3_endpoint'] ?? null,
            's3_use_path_style_endpoint' => $request->boolean('s3_use_path_style_endpoint'),
            's3_enable_usage_guard' => $request->boolean('s3_enable_usage_guard', true),
            'queue_connection' => $validated['queue_connection'],
            'queue_max_attempts' => $validated['queue_max_attempts'],
            'queue_backoff' => $validated['queue_backoff'],
            'analytics_enabled' => $request->boolean('analytics_enabled', true),
        ]);

        if ($settings->avoid_s3) {
            $settings->default_storage_type = 'local';
        }

        $settings->save();
        $settings->applyToConfig();
        Cache::forget('s3-usage-snapshot-' . now()->format('Ym'));

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Storage settings updated successfully.',
                'settings' => $settings->fresh(),
            ]);
        }

        return redirect()
            ->route('admin.storage.index')
            ->with('success', 'Storage settings updated successfully.');
    }

    public function testConnection(Request $request, S3UsageService $usageService): JsonResponse
    {
        $request->validate([
            's3_access_key' => ['required', 'string'],
            's3_secret_key' => ['required', 'string'],
            's3_region' => ['required', 'string'],
            's3_bucket' => ['required', 'string'],
            's3_endpoint' => ['nullable', 'url'],
            's3_use_path_style_endpoint' => ['sometimes', 'boolean'],
        ]);

        $config = [
            'driver' => 's3',
            'key' => $request->input('s3_access_key'),
            'secret' => $request->input('s3_secret_key'),
            'region' => $request->input('s3_region'),
            'bucket' => $request->input('s3_bucket'),
            'endpoint' => $request->input('s3_endpoint'),
            'use_path_style_endpoint' => $request->boolean('s3_use_path_style_endpoint'),
        ];

        try {
            $disk = Storage::build($config);
            $disk->files('/');

            return response()->json([
                'status' => 'success',
                'message' => 'Connection to S3 bucket established successfully.',
            ]);
        } catch (\Throwable $e) {
            Log::error('S3 connection test failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function toggleAvoidS3(Request $request): JsonResponse
    {
        $request->validate([
            'avoid_s3' => ['required', 'boolean'],
        ]);

        $settings = StorageSetting::getSettings();
        $settings->avoid_s3 = $request->boolean('avoid_s3');

        if ($settings->avoid_s3) {
            $settings->default_storage_type = 'local';
        }

        $settings->save();
        $settings->applyToConfig();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $settings->avoid_s3 ? 'S3 usage disabled.' : 'S3 usage enabled.',
            ]);
        }

        return redirect()
            ->route('admin.storage.index')
            ->with('success', $settings->avoid_s3 ? 'S3 usage disabled.' : 'S3 usage enabled.');
    }

    public function runQueue(Request $request): JsonResponse
    {
        $request->validate([
            'max_jobs' => ['nullable', 'integer', 'min:1', 'max:25'],
        ]);

        $settings = StorageSetting::getSettings();
        $settings->applyToConfig();

        $maxJobs = $request->input('max_jobs', 5);

        if ($settings->queue_connection === 'sync') {
            return response()->json([
                'status' => 'error',
                'message' => 'Queue connection is set to sync. Switch to database queue to process jobs asynchronously.',
            ], 422);
        }

        $log = QueueRunLog::create([
            'started_at' => now(),
            'status' => 'running',
            'notes' => 'Manual run from Storage Management',
        ]);

        $processed = 0;
        $failedBefore = DB::table('failed_jobs')->count();

        try {
            for ($i = 0; $i < $maxJobs; $i++) {
                $pendingBefore = DB::table('jobs')->count();

                Artisan::call('queue:work', [
                    $settings->queue_connection,
                    '--once' => true,
                    '--sleep' => 0,
                    '--tries' => $settings->queue_max_attempts,
                    '--backoff' => $settings->queue_backoff,
                    '--timeout' => 120,
                    '--queue' => 'default',
                    '--force' => true,
                ]);

                $pendingAfter = DB::table('jobs')->count();

                if ($pendingAfter < $pendingBefore) {
                    $processed++;
                }

                if ($pendingAfter === 0) {
                    break;
                }
            }

            $log->update([
                'finished_at' => now(),
                'status' => 'completed',
                'processed_count' => $processed,
                'failed_count' => DB::table('failed_jobs')->count() - $failedBefore,
            ]);

            return response()->json([
                'status' => 'success',
                'processed' => $processed,
                'pending' => DB::table('jobs')->count(),
                'failed' => DB::table('failed_jobs')->count(),
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'finished_at' => now(),
                'status' => 'failed',
                'notes' => $e->getMessage(),
            ]);

            Log::error('Queue runner failed', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'message' => 'Queue runner encountered an error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function usageLogs(): JsonResponse
    {
        $logs = StorageUsageLog::with('project')->latest()->paginate(25);
        return response()->json($logs);
    }

    public function queueLogs(): JsonResponse
    {
        $logs = QueueRunLog::latest()->paginate(25);
        return response()->json($logs);
    }

    public function clearLogs(Request $request): JsonResponse|RedirectResponse
    {
        DB::table('failed_jobs')->truncate();
        StorageUsageLog::truncate();
        QueueRunLog::truncate();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'All storage and queue logs have been cleared.',
            ]);
        }

        return redirect()
            ->route('admin.storage.index')
            ->with('success', 'All storage and queue logs have been cleared.');
    }
}

