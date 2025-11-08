<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $projectCount = Project::count();
        $activeProjects = Project::where('is_active', true)->count();

        $stats = [
            'total_projects' => $projectCount,
            'active_projects' => $activeProjects,
            'total_categories' => Category::count(),
            'total_downloads' => Project::sum('download_count'),
            'hero_sliders' => Slider::count(),
            'paid_projects' => Project::where('type', 'paid')->count(),
            'free_projects' => Project::where('type', 'free')->count(),
            'image_projects' => Project::where('file_type', 'image')->count(),
            'video_projects' => Project::where('file_type', 'video')->count(),
        ];

        $recentProjects = Project::with(['category', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $storageUsage = $this->calculateStorageUsage();
        $storageCapacityBytes = 10 * 1024 * 1024 * 1024; // 10 GB default capacity

        $systemStats = [
            'storage_used_bytes' => $storageUsage['bytes'],
            'storage_used_formatted' => $storageUsage['formatted'],
            'storage_capacity_bytes' => $storageCapacityBytes,
            'storage_capacity_formatted' => $this->formatBytes($storageCapacityBytes, 0),
            'storage_percentage' => $storageCapacityBytes > 0
                ? round(min(100, ($storageUsage['bytes'] / $storageCapacityBytes) * 100), 1)
                : 0,
            'total_files' => $storageUsage['file_count'],
            'last_login' => auth()->user()?->updated_at,
        ];

        return view('admin.dashboard', compact('stats', 'recentProjects', 'systemStats'));
    }

    /**
     * Calculate storage usage for the public disk.
     */
    protected function calculateStorageUsage(): array
    {
        try {
            $disk = Storage::disk('public');
            $files = $disk->allFiles();
            $totalSize = 0;

            foreach ($files as $file) {
                $totalSize += $disk->size($file);
            }

            return [
                'bytes' => $totalSize,
                'formatted' => $this->formatBytes($totalSize),
                'file_count' => count($files),
            ];
        } catch (\Throwable $e) {
            return [
                'bytes' => 0,
                'formatted' => '0 B',
                'file_count' => 0,
            ];
        }
    }

    /**
     * Format a byte value into a human readable string.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $power = (int) floor(log($bytes, 1024));
        $power = min($power, count($units) - 1);

        $value = $bytes / (1024 ** $power);

        return round($value, $precision) . ' ' . $units[$power];
    }
}
