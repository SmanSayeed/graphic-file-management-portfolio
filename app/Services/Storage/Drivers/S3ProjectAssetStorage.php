<?php

namespace App\Services\Storage\Drivers;

use App\Models\Project;
use App\Models\StorageSetting;
use App\Services\Storage\Contracts\ProjectAssetStorage;
use App\Services\Storage\Data\AssetUploadData;
use App\Services\Storage\Data\StoredAssetPaths;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter as FlysystemAwsS3V3Adapter;
use League\MimeTypeDetection\ExtensionMimeTypeDetector;
use Illuminate\Filesystem\AwsS3V3Adapter as LaravelAwsS3V3Adapter;

class S3ProjectAssetStorage implements ProjectAssetStorage
{
    protected string $disk = 'project_s3';

    public function store(Project $project, AssetUploadData $payload): StoredAssetPaths
    {
        $paths = new StoredAssetPaths();

        if ($payload->thumbnailTempPath) {
            $paths->thumbnail = $this->storeFile($payload->thumbnailTempPath, $payload->thumbnailOriginalName, 'thumbnails');
        }

        if ($payload->imageTempPath) {
            $paths->image = $this->storeFile($payload->imageTempPath, $payload->imageOriginalName, 'images');
        }

        if ($payload->sourceTempPath) {
            $paths->source = $this->storeFile($payload->sourceTempPath, $payload->sourceOriginalName, 'sources');
        }

        if ($payload->videoTempPath) {
            $paths->video = $this->storeFile($payload->videoTempPath, $payload->videoOriginalName, 'videos');
        }

        return $paths;
    }

    public function delete(Project $project, StoredAssetPaths $paths): void
    {
        $disk = $this->getDisk();

        foreach (['thumbnail', 'image', 'source', 'video'] as $attribute) {
            $path = $paths->{$attribute};
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    public function url(string $path): string
    {
        try {
            // Cache temporary URLs for 23 hours (slightly less than 24h validity to ensure freshness)
            // This prevents regenerating URLs on every request, which can cause 503 errors
            $cacheKey = "s3_temp_url_{$path}";
            $cachedUrl = \Illuminate\Support\Facades\Cache::get($cacheKey);

            if ($cachedUrl && filter_var($cachedUrl, FILTER_VALIDATE_URL)) {
                return $cachedUrl;
            }

            $disk = $this->getDisk();

            // Try to get a temporary URL first (works even with Block Public Access)
            // Temporary URLs are valid for 24 hours
            // FilesystemAdapter should automatically detect TemporaryUrlGenerator interface
            try {
                $tempUrl = $disk->temporaryUrl($path, now()->addHours(24));
                if (!empty($tempUrl) && filter_var($tempUrl, FILTER_VALIDATE_URL)) {
                    // Cache the URL for 23 hours
                    \Illuminate\Support\Facades\Cache::put($cacheKey, $tempUrl, now()->addHours(23));
                    return $tempUrl;
                }
            } catch (\Throwable $e) {
                Log::warning("Failed to generate temporary URL for S3 file: {$path}", [
                    'error' => $e->getMessage(),
                    'class' => get_class($e)
                ]);
            }

            // If temporary URL fails, try regular URL (works if public access is enabled)
            try {
                $regularUrl = $disk->url($path);
                if (!empty($regularUrl) && filter_var($regularUrl, FILTER_VALIDATE_URL)) {
                    Log::debug("Generated regular URL for S3 file: {$path}");
                    return $regularUrl;
                }
            } catch (\Throwable $e2) {
                Log::warning("Failed to generate regular URL for S3 file: {$path}", [
                    'error' => $e2->getMessage(),
                    'class' => get_class($e2)
                ]);
            }

            // Last resort: construct URL manually based on bucket and region
            // Note: This won't work for private files, but provides a fallback URL format
            $settings = StorageSetting::getSettings();
            if ($settings->hasValidS3Credentials()) {
                $bucket = $settings->s3_bucket;
                $region = $settings->s3_region;

                // Construct S3 URL manually
                if ($settings->s3_use_path_style_endpoint) {
                    $manualUrl = "https://s3.{$region}.amazonaws.com/{$bucket}/{$path}";
                } else {
                    $manualUrl = "https://{$bucket}.s3.{$region}.amazonaws.com/{$path}";
                }

                Log::info("Using manually constructed URL for S3 file: {$path}", [
                    'url' => $manualUrl
                ]);

                return $manualUrl;
            }

            Log::error("All URL generation methods failed for S3 file: {$path}");
            return '';
        } catch (\Throwable $e) {
            // If disk building fails, log and return empty
            Log::error("Failed to build S3 disk for URL generation: {$path}", [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return '';
        }
    }

    /**
     * Get the S3 disk with fresh configuration
     * This ensures credentials are always up-to-date
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function getDisk()
    {
        $settings = StorageSetting::getSettings();
        $settings->applyToConfig();

        // Clear any cached disk instances to ensure fresh configuration
        if (method_exists(Storage::class, 'forgetDisk')) {
            Storage::forgetDisk('project_s3');
        }

        $config = config('filesystems.disks.project_s3');

        if (!$config || !isset($config['key']) || !isset($config['secret'])) {
            throw new \RuntimeException('S3 configuration is missing or incomplete. Please check storage settings.');
        }

        $config['throw'] = true;

        try {
            // Always build the adapter manually to ensure it has temporary URL support
            // This matches what we do in AppServiceProvider
            $client = new S3Client([
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region' => $config['region'],
                'version' => 'latest',
                'endpoint' => $config['endpoint'] ?? null,
                'use_path_style_endpoint' => $config['use_path_style_endpoint'] ?? false,
            ]);

            // Use ExtensionMimeTypeDetector if finfo is not available
            $mimeTypeDetector = null;
            if (!class_exists('finfo') && !function_exists('finfo_open')) {
                $mimeTypeDetector = new ExtensionMimeTypeDetector();
            }

            $flysystemAdapter = new FlysystemAwsS3V3Adapter(
                $client,
                $config['bucket'],
                $config['prefix'] ?? '',
                null, // visibility converter (uses default)
                $mimeTypeDetector // MIME type detector (null = use default, ExtensionMimeTypeDetector if finfo unavailable)
            );

            // Create Filesystem with the adapter
            $filesystem = new \League\Flysystem\Filesystem($flysystemAdapter, $config);

            // Use Laravel's AwsS3V3Adapter which properly handles temporary URLs
            // This is Laravel's wrapper that provides temporary URL support
            // Constructor: (FilesystemOperator $driver, FlysystemAdapter $adapter, array $config, S3Client $client)
            return new LaravelAwsS3V3Adapter(
                $filesystem,
                $flysystemAdapter,
                $config,
                $client
            );
        } catch (\Exception $e) {
            Log::error("Failed to build S3 disk instance", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    protected function storeFile(string $tempPath, ?string $originalName, string $type): string
    {
        $disk = $this->getDisk();
        $tempDisk = Storage::disk('local');
        $directory = $this->resolveDirectory($type);
        $filename = $this->generateFilename($originalName);

        if (!$tempDisk->exists($tempPath)) {
            throw new \RuntimeException("Temporary file [{$tempPath}] does not exist.");
        }

        $contents = $tempDisk->get($tempPath);
        if ($contents === false || $contents === null) {
            throw new \RuntimeException("Unable to read temporary file [{$tempPath}] from local disk.");
        }

        $path = "{$directory}/{$filename}";

        try {
            // Upload without ACL to work with Block Public Access enabled
            $result = $disk->put($path, $contents);

            if ($result === false) {
                throw new \RuntimeException("S3 upload failed: put() returned false");
            }

            // Verify file exists on S3
            if (!$disk->exists($path)) {
                throw new \RuntimeException("File upload appeared to succeed but file does not exist on S3: {$path}");
            }

            return $path;
        } catch (\Exception $e) {
            Log::error("Failed to upload file to S3", [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            throw new \RuntimeException("Failed to upload file to S3: " . $e->getMessage(), 0, $e);
        }
    }

    protected function generateFilename(?string $originalName): string
    {
        $extension = $originalName ? pathinfo($originalName, PATHINFO_EXTENSION) : 'dat';
        return Str::uuid() . ($extension ? ".{$extension}" : '');
    }

    protected function resolveDirectory(string $type): string
    {
        $settings = StorageSetting::getSettings();
        $base = trim($settings->s3_prefix ?? '', '/');

        if ($base === '') {
            return "projects/{$type}";
        }

        return "{$base}/projects/{$type}";
    }
}
