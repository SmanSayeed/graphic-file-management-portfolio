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
        'processing_status',
        'processing_job_id',
        'processing_error',
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

        if ($this->storage_type === 's3') {
            // Cache the URL generation to prevent multiple calls for the same path
            // This helps prevent 503 errors when loading many projects
            $cacheKey = "project_s3_url_{$this->id}_{$path}";
            $cachedUrl = \Illuminate\Support\Facades\Cache::get($cacheKey);

            if ($cachedUrl !== null) {
                return $cachedUrl ?: null;
            }

            // Use S3ProjectAssetStorage to generate URLs (handles finfo issues)
            // This ensures we always use a fresh disk instance with ExtensionMimeTypeDetector
            try {
                $storage = app(\App\Services\Storage\Drivers\S3ProjectAssetStorage::class);
                $url = $storage->url($path);

                // Validate URL before returning
                if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                    // Cache the URL for 23 hours (slightly less than temporary URL validity)
                    \Illuminate\Support\Facades\Cache::put($cacheKey, $url, now()->addHours(23));
                    return $url;
                }

                // Cache null result for 5 minutes to prevent repeated failures
                \Illuminate\Support\Facades\Cache::put($cacheKey, false, now()->addMinutes(5));

                // If URL is empty or invalid, log and return null
                \Illuminate\Support\Facades\Log::warning("S3 URL generation returned invalid URL", [
                    'project_id' => $this->id,
                    'path' => $path,
                    'url' => $url
                ]);

                return null;
            } catch (\Throwable $e) {
                // Cache null result for 5 minutes to prevent repeated failures
                \Illuminate\Support\Facades\Cache::put($cacheKey, false, now()->addMinutes(5));

                // Log error but don't expose it to user
                \Illuminate\Support\Facades\Log::error("Failed to generate S3 URL for project asset", [
                    'project_id' => $this->id,
                    'path' => $path,
                    'error' => $e->getMessage(),
                    'class' => get_class($e)
                ]);
                return null;
            }
        }

        // For local storage
        try {
            $url = Storage::disk('project_local')->url($path);
            return !empty($url) ? $url : null;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate local URL for project asset", [
                'project_id' => $this->id,
                'path' => $path,
                'error' => $e->getMessage()
            ]);
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
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
