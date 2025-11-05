<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectLike extends Model
{
    protected $fillable = [
        'user_id',
        'project_id'
    ];

    /**
     * Get the user that owns the like.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the project that owns the like.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
