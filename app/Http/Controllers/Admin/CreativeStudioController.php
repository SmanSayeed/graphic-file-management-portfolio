<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreativeStudioRequest;
use App\Models\CreativeStudioSection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CreativeStudioController extends Controller
{
    /**
     * Display the creative studio content edit form.
     */
    public function edit(): View
    {
        $section = CreativeStudioSection::first();

        if (! $section) {
            $section = CreativeStudioSection::create([
                'section_title' => 'About Creative Studio',
                'section_subtitle' => 'Crafting exceptional designs that elevate brands and captivate audiences worldwide',
                'profile_name' => "Hi, I'm Jane Doe",
                'profile_role' => 'Creative Graphic Designer & Brand Strategist',
                'cta_text' => 'Hire Me',
                'cta_link' => '#contact',
            ]);
        }

        return view('admin.creative-studio.edit', compact('section'));
    }

    /**
     * Update the creative studio content.
     */
    public function update(CreativeStudioRequest $request): RedirectResponse
    {
        $section = CreativeStudioSection::first();

        if (! $section) {
            $section = new CreativeStudioSection();
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($section->image_path && Storage::disk('public')->exists($section->image_path)) {
                Storage::disk('public')->delete($section->image_path);
            }

            $data['image_path'] = $request->file('image')->store('creative-studio', 'public');
        }

        if (! isset($data['cta_link']) || trim($data['cta_link']) === '') {
            $data['cta_link'] = '#contact';
        }

        $section->fill($data);
        $section->save();

        return redirect()
            ->route('admin.creative-studio.edit')
            ->with('success', 'Creative studio section updated successfully.');
    }
}

