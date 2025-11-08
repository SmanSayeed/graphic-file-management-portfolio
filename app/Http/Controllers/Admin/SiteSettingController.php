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
            'favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg,gif|max:1024',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('site', 'public');
        } else {
            $validated['logo'] = $settings->logo;
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('site', 'public');
        } else {
            $validated['favicon'] = $settings->favicon;
        }

        $settings->update($validated);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Site settings updated successfully');
    }
}
