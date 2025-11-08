<?php declare(strict_types=1);

namespace App\Http\Resources\Api\V1\ImageIntros;

use App\Http\Resources\Videos\VideoResource;
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
                'images' => $mediaBlock('images'),
                'videos' => $mediaBlock('videos'),
                'audio' => $mediaBlock('audio'),
                'documents' => $mediaBlock('documents'),
            ],
            'images_count' => $intro->getMedia('images')->count(),
            'videos_count' => $intro->videos()->count(),
            'documents_count' => $intro->documents()->count(),
            'books_count' => $intro->books()->count(),
            'papers_count' => $intro->papers()->count(),
            'videos' => VideoResource::collection($intro->videos),
            'documents' => $intro->documents->map(function ($document) {
                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'slug' => $document->slug,
                    'description' => $document->description,
                    'status' => $document->status,
                    'published_at' => optional($document->published_at)?->toISOString(),
                    'created_at' => optional($document->created_at)?->toISOString(),
                    'updated_at' => optional($document->updated_at)?->toISOString(),
                ];
            }),
            'books' => $intro->books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'description' => $book->description,
                    'author' => $book->author,
                    'publisher' => $book->publisher,
                    'published_year' => $book->published_year,
                    'isbn' => $book->isbn,
                    'page_count' => $book->page_count,
                    'status' => $book->status,
                    'published_at' => optional($book->published_at)?->toISOString(),
                    'created_at' => optional($book->created_at)?->toISOString(),
                    'updated_at' => optional($book->updated_at)?->toISOString(),
                ];
            }),
            'papers' => $intro->papers->map(function ($paper) {
                return [
                    'id' => $paper->id,
                    'title' => $paper->title,
                    'slug' => $paper->slug,
                    'content_html' => $paper->content_html,
                    'abstract' => $paper->abstract,
                    'authors_json' => $paper->authors_json,
                    'year' => $paper->year,
                    'journal' => $paper->journal,
                    'doi' => $paper->doi,
                    'status' => $paper->status,
                    'published_at' => optional($paper->published_at)?->toISOString(),
                    'created_at' => optional($paper->created_at)?->toISOString(),
                    'updated_at' => optional($paper->updated_at)?->toISOString(),
                    'pdf_url' => $paper->getFirstMedia('books')?->getUrl(),
                ];
            }),
        ];
    }
}


