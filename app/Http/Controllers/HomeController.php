<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\FooterContent;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Skill;
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

        $personalInfo = PersonalInfo::first();
        $skills = Skill::active()->ordered()->get();
        $contactInfo = ContactInfo::first();
        $socialLinks = SocialLink::active()->get();
        $footerContent = FooterContent::first();

        return view('welcome', compact(
            'categories',
            'projects',
            'personalInfo',
            'skills',
            'contactInfo',
            'socialLinks',
            'footerContent'
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
}
