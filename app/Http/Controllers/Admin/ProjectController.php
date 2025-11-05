<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['category', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.projects.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'type' => 'required|in:paid,free',
            'file_type' => 'required|in:image,video',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'image' => 'nullable|image|max:5120',
            'source_file' => 'nullable|file|mimes:zip,rar,7z,psd,ai|max:10240',
            'video' => 'nullable|mimes:mp4,avi,mov,wmv|max:10240',
            'video_link' => ['nullable', 'url', function ($attribute, $value, $fail) {
                if ($value && !preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)/', $value)) {
                    $fail('The video link must be a valid YouTube URL.');
                }
            }],
        ];

        // Make thumbnail required only if file_type is image
        if ($request->file_type === 'image') {
            $rules['thumbnail'] = 'required|image|max:2048';
        }

        $validated = $request->validate($rules);

        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);

        // Handle file uploads
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('projects/images', 'public');
        }

        if ($request->hasFile('source_file')) {
            $validated['source_file'] = $request->file('source_file')->store('projects/sources', 'public');
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('projects/videos', 'public');
        }

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['category', 'user']);
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $categories = Category::active()->get();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'type' => 'required|in:paid,free',
            'file_type' => 'required|in:image,video',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'image' => 'nullable|image|max:5120',
            'source_file' => 'nullable|file|mimes:zip,rar,7z,psd,ai|max:10240',
            'video' => 'nullable|mimes:mp4,avi,mov,wmv|max:10240',
            'video_link' => ['nullable', 'url', function ($attribute, $value, $fail) {
                if ($value && !preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)/', $value)) {
                    $fail('The video link must be a valid YouTube URL.');
                }
            }],
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        // Handle file uploads
        if ($request->hasFile('thumbnail')) {
            if ($project->thumbnail) {
                Storage::disk('public')->delete($project->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $validated['image'] = $request->file('image')->store('projects/images', 'public');
        }

        if ($request->hasFile('source_file')) {
            if ($project->source_file) {
                Storage::disk('public')->delete($project->source_file);
            }
            $validated['source_file'] = $request->file('source_file')->store('projects/sources', 'public');
        }

        if ($request->hasFile('video')) {
            if ($project->video) {
                Storage::disk('public')->delete($project->video);
            }
            $validated['video'] = $request->file('video')->store('projects/videos', 'public');
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // Delete associated files
        if ($project->thumbnail) {
            Storage::disk('public')->delete($project->thumbnail);
        }
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        if ($project->source_file) {
            Storage::disk('public')->delete($project->source_file);
        }
        if ($project->video) {
            Storage::disk('public')->delete($project->video);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully');
    }

    /**
     * Toggle project active status
     */
    public function toggleStatus(Project $project)
    {
        $project->update(['is_active' => !$project->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $project->is_active
        ]);
    }
}
