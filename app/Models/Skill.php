<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description',
        'percentage',
        'is_active'
    ];

    protected $casts = [
        'percentage' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active skills.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order skills by percentage.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('percentage', 'desc');
    }
}
