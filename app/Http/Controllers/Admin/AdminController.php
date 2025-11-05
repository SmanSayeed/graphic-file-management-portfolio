<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_projects' => Project::count(),
            'total_categories' => Category::count(),
            'total_downloads' => Project::sum('download_count'),
            'hero_sliders' => 5, // Placeholder - implement when slider table is ready
        ];

        $recentProjects = Project::with(['category', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProjects'));
    }
}
