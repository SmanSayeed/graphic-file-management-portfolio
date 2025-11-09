<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsMetric extends Model
{
    protected $fillable = [
        'metric_date',
        'visitors_total',
        'downloads_total',
        'bandwidth_local_bytes',
        'bandwidth_s3_bytes',
        's3_get_requests',
        's3_put_requests',
    ];

    protected $casts = [
        'metric_date' => 'date',
        'visitors_total' => 'integer',
        'downloads_total' => 'integer',
        'bandwidth_local_bytes' => 'integer',
        'bandwidth_s3_bytes' => 'integer',
        's3_get_requests' => 'integer',
        's3_put_requests' => 'integer',
    ];

    public static function today(): self
    {
        return static::firstOrCreate(
            ['metric_date' => now()->toDateString()],
            []
        );
    }
}

