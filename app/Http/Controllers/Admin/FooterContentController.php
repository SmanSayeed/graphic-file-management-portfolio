<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FooterContentRequest;
use App\Models\FooterContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FooterContentController extends Controller
{
    /**
     * Show the footer content edit form.
     */
    public function edit(): View
    {
        $footer = FooterContent::first();

        if (! $footer) {
            $footer = FooterContent::create([
                'description' => 'Creating stunning designs that captivate and inspire. Professional graphic design services for businesses and individuals worldwide.',
                'copyright_text' => '© ' . date('Y') . ' Graphic Portfolio. All rights reserved.',
            ]);
        }

        return view('admin.footer.edit', compact('footer'));
    }

    /**
     * Update footer content.
     */
    public function update(FooterContentRequest $request): RedirectResponse
    {
        $footer = FooterContent::first();

        if (! $footer) {
            $footer = new FooterContent();
        }

        $footer->fill($request->validated());
        $footer->save();

        return redirect()
            ->route('admin.footer.edit')
            ->with('success', 'Footer content updated successfully.');
    }
}

