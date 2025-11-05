<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('projects')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|max:255|unique:categories',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->loadCount('projects');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Auto-generate slug if name changed and slug not provided
        if ($category->name !== $validated['name'] && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            // Keep existing slug if not changed
            $validated['slug'] = $validated['slug'] ?? $category->slug;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has projects
        if ($category->projects()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with existing projects. Please reassign or delete projects first.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully');
    }

    /**
     * Toggle category active status
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $category->is_active
        ]);
    }
}
