<?php

namespace App\Services\Storage;

use App\Models\AnalyticsEvent;
use App\Models\StorageUsageLog;
use Illuminate\Support\Facades\Cache;

class S3UsageService
{
    /**
     * Get the current month's S3 usage snapshot.
     *
     * @return array{
     *     storage_bytes:int,
     *     get_requests:int,
     *     put_requests:int,
     *     egress_bytes:int,
     *     limits:array,
     *     threshold_triggered:array,
     *     force_local:bool
     * }
     */
    public function snapshot(): array
    {
        return Cache::remember('s3-usage-snapshot-' . now()->format('Ym'), now()->addMinutes(5), function () {
            $startOfMonth = now()->startOfMonth();

            $storageBytes = (int) StorageUsageLog::where('storage_type', 's3')
                ->where('action', 'upload')
                ->where('created_at', '>=', $startOfMonth)
                ->sum('bytes');

            $getRequests = StorageUsageLog::where('storage_type', 's3')
                ->where('request_type', 'GET')
                ->where('created_at', '>=', $startOfMonth)
                ->count();

            $putRequests = StorageUsageLog::where('storage_type', 's3')
                ->where('request_type', 'PUT')
                ->where('created_at', '>=', $startOfMonth)
                ->count();

            $egressBytes = (int) AnalyticsEvent::where('event_type', 'egress')
                ->where('occurred_at', '>=', $startOfMonth)
                ->whereJsonContains('meta->storage_type', 's3')
                ->sum('bytes');

            $limits = [
                'storage_bytes' => 5 * 1024 * 1024 * 1024,
                'get_requests' => 20000,
                'put_requests' => 2000,
                'egress_bytes' => 15 * 1024 * 1024 * 1024,
            ];

            $threshold = 0.8;

            $status = [
                'storage' => $storageBytes >= $limits['storage_bytes'] * $threshold,
                'get' => $getRequests >= $limits['get_requests'] * $threshold,
                'put' => $putRequests >= $limits['put_requests'] * $threshold,
                'egress' => $egressBytes >= $limits['egress_bytes'] * $threshold,
            ];

            return [
                'storage_bytes' => $storageBytes,
                'get_requests' => $getRequests,
                'put_requests' => $putRequests,
                'egress_bytes' => $egressBytes,
                'limits' => $limits,
                'threshold_triggered' => $status,
                'force_local' => in_array(true, $status, true),
            ];
        });
    }
}

