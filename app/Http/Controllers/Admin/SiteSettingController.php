<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteSettingController extends Controller
{
    /**
     * Show the form for editing site settings.
     */
    public function edit()
    {
        $settings = SiteSetting::getSettings();
        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update site settings.
     */
    public function update(Request $request)
    {
        $settings = SiteSetting::getSettings();

        // Validate site name
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
        ]);

        // Handle logo upload with manual validation (bypasses finfo)
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');

            // Manual validation without using finfo
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'svg'];
            $extension = strtolower($logo->getClientOriginalExtension());

            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->route('admin.settings.edit')
                    ->withErrors(['logo' => 'The logo must be an image file (jpeg, jpg, png, gif, or svg).'])
                    ->withInput();
            }

            // Check file size (in KB, max 2048 KB = 2MB)
            if ($logo->getSize() > 2048 * 1024) {
                return redirect()->route('admin.settings.edit')
                    ->withErrors(['logo' => 'The logo must not be larger than 2MB.'])
                    ->withInput();
            }

            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $logo->store('site', 'public');
        } else {
            $validated['logo'] = $settings->logo;
        }

        // Handle favicon upload with manual validation (bypasses finfo)
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');

            // Manual validation without using finfo
            $allowedExtensions = ['png', 'ico', 'jpg', 'jpeg', 'gif'];
            $extension = strtolower($favicon->getClientOriginalExtension());

            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->route('admin.settings.edit')
                    ->withErrors(['favicon' => 'The favicon must be an image file (png, ico, jpg, jpeg, or gif).'])
                    ->withInput();
            }

            // Check file size (in KB, max 1024 KB = 1MB)
            if ($favicon->getSize() > 1024 * 1024) {
                return redirect()->route('admin.settings.edit')
                    ->withErrors(['favicon' => 'The favicon must not be larger than 1MB.'])
                    ->withInput();
            }

            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $favicon->store('site', 'public');
        } else {
            $validated['favicon'] = $settings->favicon;
        }

        $settings->update($validated);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Site settings updated successfully');
    }
}
