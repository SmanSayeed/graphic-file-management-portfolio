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

        // Get all attributes explicitly to ensure nothing is missing
        // Cast enum values to strings to ensure they're properly serialized
        $projectData = [
            'id' => $project->id,
            'title' => $project->title,
            'slug' => $project->slug,
            'description' => $project->description,
            'short_description' => $project->short_description,
            'type' => (string) $project->type, // Cast enum to string
            'file_type' => (string) ($project->file_type ?? 'image'), // Cast enum to string, ensure it exists
            'price' => $project->price,
            'thumbnail' => $project->thumbnail,
            'image' => $project->image,
            'source_file' => $project->source_file,
            'video' => $project->video,
            'video_link' => $project->video_link, // Keep as is (string or null)
            'category_id' => $project->category_id,
            'user_id' => $project->user_id,
            'download_count' => $project->download_count,
            'like_count' => $project->like_count,
            'is_active' => $project->is_active,
            'created_at' => $project->created_at,
            'updated_at' => $project->updated_at,
        ];
        
        // Ensure category relationship is included properly
        if ($project->category) {
            $projectData['category'] = [
                'id' => $project->category->id,
                'name' => $project->category->name,
                'slug' => $project->category->slug,
            ];
        }

        // Add like information
        $user = Auth::user();
        $projectData['is_liked'] = $user && $project->isLikedBy($user);
        $projectData['like_count'] = $project->like_count;
        
        // Triple-check critical fields are strings and not null
        $projectData['file_type'] = (string) ($project->file_type ?? 'image');
        $projectData['video_link'] = $project->video_link ? (string) $project->video_link : null;
        
        // Add file extensions for download display
        $projectData['image_extension'] = $project->image ? strtoupper(pathinfo($project->image, PATHINFO_EXTENSION)) : null;
        $projectData['thumbnail_extension'] = $project->thumbnail ? strtoupper(pathinfo($project->thumbnail, PATHINFO_EXTENSION)) : null;
        $projectData['source_file_extension'] = $project->source_file ? strtoupper(pathinfo($project->source_file, PATHINFO_EXTENSION)) : null;
        $projectData['video_extension'] = $project->video ? strtoupper(pathinfo($project->video, PATHINFO_EXTENSION)) : null;
        
        // Helper function to get file type name from extension
        $getFileTypeName = function($extension) {
            $types = [
                'PSD' => 'Photoshop',
                'AI' => 'Illustrator',
                'ZIP' => 'ZIP Archive',
                'RAR' => 'RAR Archive',
                '7Z' => '7-Zip Archive',
                'MP4' => 'MP4 Video',
                'AVI' => 'AVI Video',
                'MOV' => 'MOV Video',
                'WMV' => 'WMV Video',
                'PNG' => 'PNG Image',
                'JPG' => 'JPG Image',
                'JPEG' => 'JPEG Image',
            ];
            return $types[strtoupper($extension)] ?? strtoupper($extension) . ' File';
        };
        
        $projectData['image_file_type'] = $projectData['image_extension'] ? $getFileTypeName($projectData['image_extension']) : null;
        $projectData['source_file_type'] = $projectData['source_file_extension'] ? $getFileTypeName($projectData['source_file_extension']) : null;
        $projectData['video_file_type'] = $projectData['video_extension'] ? $getFileTypeName($projectData['video_extension']) : null;

        return response()->json($projectData);
    }

    /**
     * Show projects by category
     */
    public function categoryShow($slug)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();
        $projects = Project::with(['category', 'user'])
            ->where('category_id', $category->id)
            ->active()
            ->latest()
            ->get();

        $categories = Category::active()->get();
        $siteSettings = SiteSetting::getSettings();

        return view('category.show', compact('category', 'projects', 'categories', 'siteSettings'));
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
                // Prefer image over thumbnail, but use thumbnail if image doesn't exist
                if ($project->image) {
                    $filePath = storage_path('app/public/' . $project->image);
                    $fileName = basename($project->image);
                } elseif ($project->thumbnail) {
                    $filePath = storage_path('app/public/' . $project->thumbnail);
                    $fileName = basename($project->thumbnail);
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
