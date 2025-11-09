<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageUsageLog extends Model
{
    protected $fillable = [
        'project_id',
        'storage_type',
        'action',
        'request_type',
        'path',
        'bytes',
        'status',
        'message',
    ];

    protected $casts = [
        'bytes' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

