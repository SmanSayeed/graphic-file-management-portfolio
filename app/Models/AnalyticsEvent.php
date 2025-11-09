<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    protected $fillable = [
        'event_type',
        'context',
        'project_id',
        'bytes',
        'meta',
        'occurred_at',
    ];

    protected $casts = [
        'project_id' => 'integer',
        'bytes' => 'integer',
        'meta' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

