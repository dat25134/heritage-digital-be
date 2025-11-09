<?php

declare(strict_types=1);

namespace App\Domain\ResearchPapers\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchPaperResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \App\Domain\ResearchPapers\Models\ResearchPaper $paper */
        $paper = $this->resource;

        $mediaBlock = function (string $collection) use ($paper): array {
            $mediaItems = $paper->getMedia($collection);
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
            'id' => $paper->id,
            'title' => $paper->title,
            'slug' => $paper->slug,
            'abstract' => $paper->abstract,
            'content_html' => $paper->content_html,
            'authors' => $paper->authors_json,
            'year' => $paper->year,
            'journal' => $paper->journal,
            'doi' => $paper->doi,
            'status' => $paper->status,
            'published_at' => optional($paper->published_at)?->toISOString(),
            'created_at' => optional($paper->created_at)?->toISOString(),
            'updated_at' => optional($paper->updated_at)?->toISOString(),
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


