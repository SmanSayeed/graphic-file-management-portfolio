<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'type',
        'file_type',
        'price',
        'thumbnail',
        'image',
        'source_file',
        'video',
        'video_link',
        'category_id',
        'user_id',
        'download_count',
        'like_count',
        'is_active',
        'storage_type',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'download_count' => 'integer',
        'like_count' => 'integer',
        'is_active' => 'boolean',
        'storage_type' => 'string',
    ];

    protected $appends = [
        'thumbnail_url',
        'image_url',
        'source_file_url',
        'video_url',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project->title);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('title') && empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project->title, $project->id);
            }
        });
    }

    /**
     * Get the category that owns the project.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the likes for the project.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(ProjectLike::class);
    }

    /**
     * Get the users who liked this project.
     */
    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_likes');
    }

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include free projects.
     */
    public function scopeFree($query)
    {
        return $query->where('type', 'free');
    }

    /**
     * Scope a query to only include paid projects.
     */
    public function scopePaid($query)
    {
        return $query->where('type', 'paid');
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter by file type.
     */
    public function scopeByFileType($query, $fileType)
    {
        return $query->where('file_type', $fileType);
    }

    /**
     * Check if a user has liked this project.
     */
    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Like this project by a user.
     */
    public function like(User $user): bool
    {
        if ($this->isLikedBy($user)) {
            return false;
        }

        $this->likes()->create(['user_id' => $user->id]);
        $this->increment('like_count');
        return true;
    }

    /**
     * Unlike this project by a user.
     */
    public function unlike(User $user): bool
    {
        if (!$this->isLikedBy($user)) {
            return false;
        }

        $this->likes()->where('user_id', $user->id)->delete();
        $this->decrement('like_count');
        return true;
    }

    /**
     * Increment download count.
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->generateAssetUrl($this->thumbnail);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->generateAssetUrl($this->image);
    }

    public function getSourceFileUrlAttribute(): ?string
    {
        return $this->generateAssetUrl($this->source_file);
    }

    public function getVideoUrlAttribute(): ?string
    {
        return $this->generateAssetUrl($this->video);
    }

    protected function generateAssetUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $disk = $this->storage_type === 's3' ? 'project_s3' : 'project_local';

        try {
            if ($this->storage_type === 's3') {
                // For S3, try to get a temporary URL (works even with Block Public Access)
                // Temporary URLs expire after 1 hour, but can be regenerated
                try {
                    return Storage::disk($disk)->temporaryUrl($path, now()->addHours(24));
                } catch (\Throwable $e) {
                    // If temporary URL fails, try regular URL
                    return Storage::disk($disk)->url($path);
                }
            }
            return Storage::disk($disk)->url($path);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Generate a unique slug for the project
     */
    protected static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
