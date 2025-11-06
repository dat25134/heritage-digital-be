<?php

declare(strict_types=1);

namespace App\Http\Resources\Videos;

use App\Domain\Videos\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Video */
class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'source_type' => $this->source_type,
            'file_path' => $this->file_path,
            'external_url' => $this->external_url,
            'duration_sec' => $this->duration_sec,
            'width' => $this->width,
            'height' => $this->height,
            'size_bytes' => $this->size_bytes,
            'mime' => $this->mime,
            'status' => $this->status,
            'published_at' => optional($this->published_at)?->toIso8601String(),
            'sort_order' => $this->sort_order,
            'thumbnail' => [
                'original' => $this->getFirstMediaUrl('thumbnail') ?: null,
                'thumb' => $this->getFirstMediaUrl('thumbnail', 'thumb') ?: null,
                'sm' => $this->getFirstMediaUrl('thumbnail', 'sm') ?: null,
                'md' => $this->getFirstMediaUrl('thumbnail', 'md') ?: null,
                'lg' => $this->getFirstMediaUrl('thumbnail', 'lg') ?: null,
            ],
            'created_at' => optional($this->created_at)?->toIso8601String(),
            'updated_at' => optional($this->updated_at)?->toIso8601String(),
        ];
    }
}


