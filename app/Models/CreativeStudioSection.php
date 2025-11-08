<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreativeStudioSection extends Model
{
    protected $fillable = [
        'section_title',
        'section_subtitle',
        'profile_name',
        'profile_role',
        'image_path',
        'description',
        'highlight_one',
        'highlight_two',
        'highlight_three',
        'highlight_four',
        'cta_text',
        'cta_link',
    ];

    /**
     * Determine if the section has highlight content.
     */
    public function hasHighlights(): bool
    {
        return collect([
            $this->highlight_one,
            $this->highlight_two,
            $this->highlight_three,
            $this->highlight_four,
        ])->filter()->isNotEmpty();
    }
}

