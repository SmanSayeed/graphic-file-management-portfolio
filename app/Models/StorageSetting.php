<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class StorageSetting extends Model
{
    protected $fillable = [
        'default_storage_type',
        'avoid_s3',
        's3_access_key',
        's3_secret_key',
        's3_region',
        's3_bucket',
        's3_prefix',
        's3_endpoint',
        's3_use_path_style_endpoint',
        's3_enable_usage_guard',
        'queue_connection',
        'queue_max_attempts',
        'queue_backoff',
        'analytics_enabled',
    ];

    protected $casts = [
        'avoid_s3' => 'boolean',
        's3_use_path_style_endpoint' => 'boolean',
        's3_enable_usage_guard' => 'boolean',
        'queue_max_attempts' => 'integer',
        'queue_backoff' => 'integer',
        'analytics_enabled' => 'boolean',
    ];

    public static function getSettings(): self
    {
        return static::first() ?? static::create();
    }

    public function setS3SecretKeyAttribute(?string $value): void
    {
        $this->attributes['s3_secret_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getS3SecretKeyAttribute(?string $value): ?string
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setS3AccessKeyAttribute(?string $value): void
    {
        $this->attributes['s3_access_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getS3AccessKeyAttribute(?string $value): ?string
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getDiskNameAttribute(): string
    {
        return $this->default_storage_type === 's3' ? 'project_s3' : 'project_local';
    }

    public function hasValidS3Credentials(): bool
    {
        return filled($this->s3_access_key)
            && filled($this->s3_secret_key)
            && filled($this->s3_bucket)
            && filled($this->s3_region);
    }

    public function applyToConfig(): void
    {
        config([
            'filesystems.disks.project_local' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => env('APP_URL') . '/storage',
                'visibility' => 'public',
            ],
        ]);

        if ($this->hasValidS3Credentials()) {
            config([
                'filesystems.disks.project_s3' => [
                    'driver' => 's3',
                    'key' => $this->s3_access_key,
                    'secret' => $this->s3_secret_key,
                    'region' => $this->s3_region,
                    'bucket' => $this->s3_bucket,
                    'url' => null,
                    'endpoint' => $this->s3_endpoint,
                    'use_path_style_endpoint' => $this->s3_use_path_style_endpoint,
                    'visibility' => 'public',
                ],
            ]);
        }
    }
}
