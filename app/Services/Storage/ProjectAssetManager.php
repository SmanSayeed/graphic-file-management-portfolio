<?php

namespace App\Services\Storage;

use App\Models\Project;
use App\Models\StorageSetting;
use App\Services\Storage\Contracts\ProjectAssetStorage;
use App\Services\Storage\Data\AssetUploadData;
use App\Services\Storage\Data\StoredAssetPaths;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Arr;

class ProjectAssetManager
{
    public function __construct(
        protected Container $container
    ) {
    }

    public function store(Project $project, AssetUploadData $payload, ?string $storageType = null): StoredAssetPaths
    {
        $driver = $this->resolveDriver($storageType ?? $project->storage_type ?? $this->defaultStorageType());
        return $driver->store($project, $payload);
    }

    public function delete(Project $project, StoredAssetPaths $paths, ?string $storageType = null): void
    {
        $driver = $this->resolveDriver($storageType ?? $project->storage_type ?? $this->defaultStorageType());
        $driver->delete($project, $paths);
    }

    public function url(string $path, string $storageType): string
    {
        return $this->resolveDriver($storageType)->url($path);
    }

    protected function resolveDriver(string $storageType): ProjectAssetStorage
    {
        $map = [
            'local' => Drivers\LocalProjectAssetStorage::class,
            's3' => Drivers\S3ProjectAssetStorage::class,
        ];

        $class = Arr::get($map, $storageType, $map['local']);

        return $this->container->make($class);
    }

    protected function defaultStorageType(): string
    {
        return StorageSetting::getSettings()->default_storage_type ?? 'local';
    }
}

