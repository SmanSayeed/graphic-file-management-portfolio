<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactInfoRequest;
use App\Models\ContactInfo;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactInfoController extends Controller
{
    /**
     * Show the form for editing the contact information.
     */
    public function edit(): View
    {
        $contact = ContactInfo::first();

        if (! $contact) {
            $contact = ContactInfo::create([
                'phone' => '+1 (555) 123-4567',
                'email' => 'hello@example.com',
                'address' => '123 Design Street, Creative City, CC 12345',
                'alternative_email' => 'support@example.com',
                'website' => 'https://example.com',
            ]);
        }

        return view('admin.contact.edit', compact('contact'));
    }

    /**
     * Update the contact information in storage.
     */
    public function update(ContactInfoRequest $request): RedirectResponse
    {
        $contact = ContactInfo::first();

        if (! $contact) {
            $contact = new ContactInfo();
        }

        $contact->fill($request->validated());
        $contact->save();

        return redirect()
            ->route('admin.contact.edit')
            ->with('success', 'Contact information updated successfully.');
    }
}

