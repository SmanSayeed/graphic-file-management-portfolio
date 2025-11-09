<?php

namespace App\Services\Storage\Data;

class StoredAssetPaths
{
    public function __construct(
        public ?string $thumbnail = null,
        public ?string $image = null,
        public ?string $source = null,
        public ?string $video = null,
    ) {
    }

    public static function fromArray(array $paths): self
    {
        return new self(
            $paths['thumbnail'] ?? null,
            $paths['image'] ?? null,
            $paths['source'] ?? $paths['source_file'] ?? null,
            $paths['video'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'thumbnail' => $this->thumbnail,
            'image' => $this->image,
            'source_file' => $this->source,
            'video' => $this->video,
        ];
    }
}

