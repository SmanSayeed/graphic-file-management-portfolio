<?php

namespace App\Services\Storage\Drivers;

use App\Models\Project;
use App\Models\StorageSetting;
use App\Services\Storage\Contracts\ProjectAssetStorage;
use App\Services\Storage\Data\AssetUploadData;
use App\Services\Storage\Data\StoredAssetPaths;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalProjectAssetStorage implements ProjectAssetStorage
{
    protected string $disk = 'project_local';

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
        $disk = Storage::disk($this->disk);

        foreach (['thumbnail', 'image', 'source', 'video'] as $attribute) {
            $path = $paths->{$attribute};
            if ($path && $disk->exists($path)) {
                $disk->delete($path);
            }
        }
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    protected function storeFile(string $tempPath, ?string $originalName, string $type): string
    {
        $disk = Storage::disk($this->disk);
        $tempDisk = Storage::disk('local');
        $directory = $this->resolveDirectory($type);

        $filename = $this->generateFilename($originalName);
        if (!$tempDisk->exists($tempPath)) {
            throw new \RuntimeException("Temporary file [{$tempPath}] does not exist.");
        }

        $stream = $tempDisk->readStream($tempPath);
        if ($stream === false) {
            throw new \RuntimeException("Unable to read temporary file [{$tempPath}] from local disk.");
        }

        $disk->put("{$directory}/{$filename}", $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return "{$directory}/{$filename}";
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

