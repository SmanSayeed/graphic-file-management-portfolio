<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QueueRunLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class QueueMonitorController extends Controller
{
    public function index()
    {
        $pending = DB::table('jobs')->count();
        $failed = DB::table('failed_jobs')->count();

        $latestRun = QueueRunLog::latest()->first();
        $recentRuns = QueueRunLog::latest()->limit(10)->get();
        $failedJobs = DB::table('failed_jobs')->orderByDesc('failed_at')->limit(10)->get();

        return view('admin.queue.index', compact(
            'pending',
            'failed',
            'latestRun',
            'recentRuns',
            'failedJobs'
        ));
    }

    public function stats(): JsonResponse
    {
        $pending = DB::table('jobs')->count();
        $failed = DB::table('failed_jobs')->count();

        $latestRun = QueueRunLog::latest()->first();
        $latestFailed = DB::table('failed_jobs')->orderByDesc('failed_at')->first();

        return response()->json([
            'pending' => $pending,
            'failed' => $failed,
            'latest_run' => $latestRun ? [
                'status' => $latestRun->status,
                'processed_count' => $latestRun->processed_count,
                'failed_count' => $latestRun->failed_count,
                'started_at' => optional($latestRun->started_at)->toIso8601String(),
                'finished_at' => optional($latestRun->finished_at)->toIso8601String(),
                'notes' => $latestRun->notes,
                'created_at' => $latestRun->created_at->toIso8601String(),
            ] : null,
            'latest_failed' => $latestFailed ? [
                'queue' => $latestFailed->queue,
                'failed_at' => $latestFailed->failed_at,
                'exception' => $latestFailed->exception,
            ] : null,
        ]);
    }
}

