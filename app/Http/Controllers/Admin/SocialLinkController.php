<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    /**
     * Supported social platforms with default icon classes.
     *
     * @return array<string, string>
     */
    protected function platforms(): array
    {
        return [
            'twitter' => 'bi-twitter',
            'instagram' => 'bi-instagram',
            'linkedin' => 'bi-linkedin',
            'dribbble' => 'bi-dribbble',
            'behance' => 'bi-behance',
            'github' => 'bi-github',
            'facebook' => 'bi-facebook',
            'youtube' => 'bi-youtube',
        ];
    }

    /**
     * Display the social links edit form.
     */
    public function edit(): View
    {
        $platforms = $this->platforms();

        // Ensure all platforms exist
        foreach ($platforms as $platform => $icon) {
            SocialLink::firstOrCreate(
                ['platform' => $platform],
                ['url' => '', 'icon' => $icon, 'is_active' => false]
            );
        }

        $socialLinks = SocialLink::orderBy('platform')->get()->keyBy('platform');

        return view('admin.social.edit', compact('socialLinks', 'platforms'));
    }

    /**
     * Update the social links.
     */
    public function update(Request $request): RedirectResponse
    {
        $platforms = collect($this->platforms());
        $links = collect($request->input('links', []));

        $rules = [];
        $platforms->each(function ($icon, $platform) use (&$rules) {
            $rules["links.$platform.url"] = ['nullable', 'url', 'max:255'];
            $rules["links.$platform.is_active"] = ['nullable', 'boolean'];
        });

        $validated = $request->validate($rules);
        $validatedLinks = collect($validated['links'] ?? []);

        $validatedLinks->each(function ($data, $platform) use ($platforms) {
            $link = SocialLink::where('platform', $platform)->first();

            if (! $link) {
                $link = new SocialLink(['platform' => $platform]);
            }

            $link->url = $data['url'] ?? '';
            $link->icon = $platforms[$platform] ?? $link->icon ?? 'bi-share';
            $link->is_active = !empty($data['is_active']);
            $link->save();
        });

        return redirect()
            ->route('admin.social.edit')
            ->with('success', 'Social links updated successfully.');
    }
}

