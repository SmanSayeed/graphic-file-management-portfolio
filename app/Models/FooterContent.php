<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterContent extends Model
{
    protected $fillable = [
        'about_text',
        'services',
        'copyright_text',
        'privacy_policy_url',
        'terms_of_service_url'
    ];

    /**
     * Get services as an array.
     */
    public function getServicesArrayAttribute(): array
    {
        return array_filter(explode("\n", $this->services));
    }
}
