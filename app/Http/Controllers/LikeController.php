<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like for a project
     */
    public function toggleLike(Project $project)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to like projects'
            ], 401);
        }

        $user = Auth::user();

        if ($project->isLikedBy($user)) {
            $project->unlike($user);
            $liked = false;
        } else {
            $project->like($user);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'is_liked' => $liked,
            'liked' => $liked, // Keep for backward compatibility
            'like_count' => $project->fresh()->like_count,
            'message' => $liked ? 'Project liked successfully' : 'Project unliked successfully'
        ]);
    }

    /**
     * Check if user has liked a project
     */
    public function checkLike(Project $project)
    {
        if (!Auth::check()) {
            return response()->json([
                'is_authenticated' => false,
                'liked' => false
            ]);
        }

        return response()->json([
            'is_authenticated' => true,
            'liked' => $project->isLikedBy(Auth::user()),
            'is_liked' => $project->isLikedBy(Auth::user())
        ]);
    }
}
