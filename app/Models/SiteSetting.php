<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'logo',
        'favicon',
    ];

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * Get the favicon URL.
     */
    public function getFaviconUrlAttribute(): ?string
    {
        if ($this->favicon) {
            return asset('storage/' . $this->favicon);
        }
        return null;
    }

    /**
     * Get single instance (singleton pattern)
     */
    public static function getSettings()
    {
        return static::first() ?? static::create([
            'site_name' => 'Creative Studio',
            'logo' => null,
            'favicon' => null,
        ]);
    }
}
