<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\FooterContent;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\Slider;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show home page
     */
    public function index()
    {
        $categories = Category::active()->get();
        $projects = Project::with(['category', 'user'])
            ->active()
            ->latest()
            ->get();

        $sliders = Slider::active()->ordered()->get();
        $personalInfo = PersonalInfo::first();
        $skills = Skill::active()->ordered()->get();
        $contactInfo = ContactInfo::first();
        $socialLinks = SocialLink::active()->get();
        $footerContent = FooterContent::first();
        $siteSettings = SiteSetting::getSettings();

        return view('welcome', compact(
            'categories',
            'projects',
            'sliders',
            'personalInfo',
            'skills',
            'contactInfo',
            'socialLinks',
            'footerContent',
            'siteSettings'
        ));
    }

    /**
     * Get project by ID (for modal)
     */
    public function getProject(Project $project)
    {
        $project->load(['category', 'user']);

        $projectData = $project->toArray();

        // Add like information
        $user = Auth::user();
        $projectData['is_liked'] = $user && $project->isLikedBy($user);
        $projectData['like_count'] = $project->like_count;

        return response()->json($projectData);
    }

    /**
     * Download project file
     */
    public function download(Project $project, Request $request)
    {
        $type = $request->query('type', 'image');

        $filePath = null;
        $fileName = null;

        switch ($type) {
            case 'image':
                if ($project->image) {
                    $filePath = storage_path('app/public/' . $project->image);
                    $fileName = basename($project->image);
                }
                break;
            case 'video':
                if ($project->video) {
                    $filePath = storage_path('app/public/' . $project->video);
                    $fileName = basename($project->video);
                }
                break;
            case 'source':
                if ($project->source_file) {
                    $filePath = storage_path('app/public/' . $project->source_file);
                    $fileName = basename($project->source_file);
                }
                break;
        }

        if ($filePath && file_exists($filePath)) {
            // Increment download count
            $project->incrementDownloadCount();

            return response()->download($filePath, $fileName);
        }

        abort(404, 'File not found');
    }
}
