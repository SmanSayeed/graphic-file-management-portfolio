<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::orderBy('sort_order', 'asc')->latest()->paginate(20);
        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'title' => 'nullable'
        ]);
        $validated['title'] = "slider";
        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        // Set default sort_order if not provided
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = Slider::max('sort_order') + 1;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider created successfully');
    }   

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return view('admin.sliders.show', compact('slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        } else {
            // Keep existing image if not updated
            $validated['image'] = $slider->image;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        // Delete image file
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Slider deleted successfully');
    }
}
