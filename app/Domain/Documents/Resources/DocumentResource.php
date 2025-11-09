<?php

declare(strict_types=1);

namespace App\Domain\Documents\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $mediaBlock = function (string $collection): array {
            $mediaItems = $this->getMedia($collection);
            if ($mediaItems->isEmpty()) {
                return [];
            }

            return $mediaItems->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'conversions' => [
                        'thumb' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : null,
                        'sm' => $media->hasGeneratedConversion('sm') ? $media->getUrl('sm') : null,
                        'md' => $media->hasGeneratedConversion('md') ? $media->getUrl('md') : null,
                        'lg' => $media->hasGeneratedConversion('lg') ? $media->getUrl('lg') : null,
                    ],
                    'custom_properties' => $media->custom_properties,
                    'created_at' => optional($media->created_at)?->toISOString(),
                ];
            })->toArray();
        };

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'status' => $this->status,
            'published_at' => optional($this->published_at)?->toISOString(),
            'created_at' => optional($this->created_at)?->toISOString(),
            'updated_at' => optional($this->updated_at)?->toISOString(),
            'media' => [
                'cover' => $mediaBlock('cover'),
                'thumb' => $mediaBlock('thumb'),
                'avatar' => $mediaBlock('avatar'),
                'images' => $mediaBlock('images'),
                'videos' => $mediaBlock('videos'),
                'audio' => $mediaBlock('audio'),
                'documents' => $mediaBlock('documents'),
            ],
        ];
    }
}


