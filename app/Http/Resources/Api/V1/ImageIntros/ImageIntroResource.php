<?php declare(strict_types=1);

namespace App\Http\Resources\Api\V1\ImageIntros;

use Illuminate\Http\Resources\Json\JsonResource;

class ImageIntroResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Domain\ImageIntros\Models\ImageIntro $intro */
        $intro = $this->resource;

        $mediaBlock = function (string $collection) use ($intro): array {
            $mediaItems = $intro->getMedia($collection);
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
            'id' => $intro->id,
            'title' => $intro->title,
            'slug' => $intro->slug,
            'summary' => $intro->summary,
            'content_html' => $intro->content_html,
            'status' => $intro->status,
            'published_at' => optional($intro->published_at)?->toISOString(),
            'created_at' => optional($intro->created_at)?->toISOString(),
            'updated_at' => optional($intro->updated_at)?->toISOString(),
            'media' => [
                'cover' => $mediaBlock('cover'),
                'thumb' => $mediaBlock('thumb'),
                'avatar' => $mediaBlock('avatar'),
                'image' => $mediaBlock('image'),
            ],
        ];
    }
}


