<?php

namespace App\Services\Storage\Contracts;

use App\Models\Project;
use App\Services\Storage\Data\AssetUploadData;
use App\Services\Storage\Data\StoredAssetPaths;

interface ProjectAssetStorage
{
    /**
     * Store provided assets and return stored paths.
     */
    public function store(Project $project, AssetUploadData $payload): StoredAssetPaths;

    /**
     * Delete previously stored assets.
     */
    public function delete(Project $project, StoredAssetPaths $paths): void;

    /**
     * Generate a download URL for an asset path.
     */
    public function url(string $path): string;
}

