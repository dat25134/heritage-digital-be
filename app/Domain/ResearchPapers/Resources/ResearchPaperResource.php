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

        // Load all media once and group by collection to avoid N+1 queries
        // Use relationship if eager loaded, otherwise load it
        $allMedia = $paper->relationLoaded('media') 
            ? $paper->getRelation('media') 
            : $paper->getMedia();
        $mediaByCollection = $allMedia->groupBy('collection_name');

        $formatMedia = function ($media) {
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
        };

        $getMediaCollection = function (string $collection) use ($mediaByCollection, $formatMedia): array {
            $mediaItems = $mediaByCollection->get($collection);
            if (!$mediaItems || $mediaItems->isEmpty()) {
                return [];
            }
            return $mediaItems->map($formatMedia)->toArray();
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
                'cover' => $getMediaCollection('cover'),
                'thumb' => $getMediaCollection('thumb'),
                'avatar' => $getMediaCollection('avatar'),
                'images' => $getMediaCollection('images'),
                'videos' => $getMediaCollection('videos'),
                'audio' => $getMediaCollection('audio'),
                'documents' => $getMediaCollection('documents'),
                'books' => $getMediaCollection('books'),
            ],
        ];
    }
}


