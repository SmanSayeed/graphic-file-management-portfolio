<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreativeStudioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'section_title' => ['nullable', 'string', 'max:255'],
            'section_subtitle' => ['nullable', 'string', 'max:255'],
            'profile_name' => ['nullable', 'string', 'max:255'],
            'profile_role' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'highlight_one' => ['nullable', 'string', 'max:255'],
            'highlight_two' => ['nullable', 'string', 'max:255'],
            'highlight_three' => ['nullable', 'string', 'max:255'],
            'highlight_four' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:2048'],
        ];
    }
}

