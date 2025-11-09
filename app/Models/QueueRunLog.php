<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueRunLog extends Model
{
    protected $fillable = [
        'started_at',
        'finished_at',
        'status',
        'processed_count',
        'failed_count',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'processed_count' => 'integer',
        'failed_count' => 'integer',
    ];
}

