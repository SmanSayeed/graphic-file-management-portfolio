<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueStatusController extends Controller
{
    /**
     * Check the status of a project's processing job
     */
    public function checkProjectStatus(Request $request, Project $project)
    {
        $status = $project->processing_status ?? 'completed';
        $jobId = $project->processing_job_id;
        
        // Check if job still exists in queue
        $jobExists = false;
        $jobFailed = false;
        
        if ($jobId) {
            // Check if job is still pending in jobs table
            $jobExists = DB::table('jobs')
                ->where('id', $jobId)
                ->exists();
            
            // Check if job failed
            $jobFailed = DB::table('failed_jobs')
                ->where('id', $jobId)
                ->exists();
        }
        
        // Determine actual status
        if ($status === 'pending' && !$jobExists && !$jobFailed) {
            // Job was processed but status wasn't updated - check project status
            $project->refresh();
            $status = $project->processing_status ?? 'completed';
        } elseif ($status === 'pending' && $jobFailed) {
            $status = 'failed';
        } elseif ($status === 'pending' && $jobExists) {
            $status = 'pending';
        }
        
        return response()->json([
            'status' => $status,
            'job_id' => $jobId,
            'job_exists' => $jobExists,
            'error' => $project->processing_error,
            'message' => $this->getStatusMessage($status),
        ]);
    }
    
    /**
     * Check status by job ID
     */
    public function checkJobStatus(Request $request, $jobId)
    {
        if (!$jobId) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Job ID not provided',
            ], 404);
        }
        
        // First check if project was updated (most reliable)
        $project = Project::where('processing_job_id', $jobId)->first();
        
        if ($project) {
            $status = $project->processing_status ?? 'completed';
            
            // If status is still pending, check if job exists
            if ($status === 'pending') {
                $jobExists = DB::table('jobs')
                    ->where('id', $jobId)
                    ->exists();
                
                if (!$jobExists) {
                    // Job was processed but status wasn't updated - refresh project
                    $project->refresh();
                    $status = $project->processing_status ?? 'completed';
                }
            }
            
            return response()->json([
                'status' => $status,
                'job_id' => $jobId,
                'project_id' => $project->id,
                'error' => $project->processing_error,
                'message' => $this->getStatusMessage($status),
            ]);
        }
        
        // Check if job exists in queue
        $jobExists = DB::table('jobs')
            ->where('id', $jobId)
            ->exists();
        
        // Check if job failed
        $failedJob = DB::table('failed_jobs')
            ->where('id', $jobId)
            ->first();
        
        if ($failedJob) {
            return response()->json([
                'status' => 'failed',
                'job_id' => $jobId,
                'error' => $failedJob->exception ?? 'Unknown error',
                'message' => 'Job processing failed',
            ]);
        }
        
        if ($jobExists) {
            return response()->json([
                'status' => 'pending',
                'job_id' => $jobId,
                'message' => 'Job is still processing',
            ]);
        }
        
        return response()->json([
            'status' => 'completed',
            'job_id' => $jobId,
            'message' => 'Job completed',
        ]);
    }
    
    /**
     * Get status message for display
     */
    protected function getStatusMessage(string $status): string
    {
        return match($status) {
            'pending' => 'Files are queued for upload. Please wait...',
            'processing' => 'Files are being uploaded. Please wait...',
            'completed' => 'Files uploaded successfully!',
            'failed' => 'Upload failed. Please try again.',
            default => 'Unknown status',
        };
    }
}
