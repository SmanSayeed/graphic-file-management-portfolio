<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\AnalyticsMetric;
use App\Models\Project;
use App\Services\Storage\S3UsageService;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    public function index(S3UsageService $usageService)
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30)->toDateString();

        $metrics = AnalyticsMetric::where('metric_date', '>=', $thirtyDaysAgo)
            ->orderByDesc('metric_date')
            ->get();

        $totals = [
            'visitors' => $metrics->sum('visitors_total'),
            'downloads' => $metrics->sum('downloads_total'),
            'bandwidth_local' => $metrics->sum('bandwidth_local_bytes'),
            'bandwidth_s3' => $metrics->sum('bandwidth_s3_bytes'),
            's3_get_requests' => $metrics->sum('s3_get_requests'),
            's3_put_requests' => $metrics->sum('s3_put_requests'),
        ];

        $topProjects = Project::orderByDesc('download_count')
            ->limit(5)
            ->get();

        $recentEvents = AnalyticsEvent::latest()->limit(25)->get();

        $usageSnapshot = $usageService->snapshot();

        return view('admin.analytics.index', compact(
            'metrics',
            'totals',
            'topProjects',
            'recentEvents',
            'usageSnapshot'
        ));
    }
}

