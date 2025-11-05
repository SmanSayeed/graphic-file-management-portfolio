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

        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('site', 'public');
        } else {
            // Keep existing logo if no new file uploaded
            $validated['logo'] = $settings->logo;
        }

        $settings->update($validated);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Site settings updated successfully');
    }
}
