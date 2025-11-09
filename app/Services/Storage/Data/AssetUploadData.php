<?php

namespace App\Services\Storage\Data;

class AssetUploadData
{
    public function __construct(
        public readonly ?string $thumbnailTempPath,
        public readonly ?string $imageTempPath,
        public readonly ?string $sourceTempPath,
        public readonly ?string $videoTempPath,
        public readonly ?string $thumbnailOriginalName,
        public readonly ?string $imageOriginalName,
        public readonly ?string $sourceOriginalName,
        public readonly ?string $videoOriginalName,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['thumbnail_temp_path'] ?? null,
            $data['image_temp_path'] ?? null,
            $data['source_temp_path'] ?? null,
            $data['video_temp_path'] ?? null,
            $data['thumbnail_original_name'] ?? null,
            $data['image_original_name'] ?? null,
            $data['source_original_name'] ?? null,
            $data['video_original_name'] ?? null,
        );
    }
}

