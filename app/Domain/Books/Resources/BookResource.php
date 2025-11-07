<?php

declare(strict_types=1);

namespace App\Domain\Books\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cover = $this->getFirstMedia('cover');
        $ebook = $this->getFirstMedia('ebook');
        $attachments = $this->getMedia('attachments');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'published_year' => $this->published_year,
            'isbn' => $this->isbn,
            'page_count' => $this->page_count,
            'status' => $this->status,
            'published_at' => optional($this->published_at)?->toISOString(),
            'created_at' => optional($this->created_at)?->toISOString(),
            'updated_at' => optional($this->updated_at)?->toISOString(),
            'media' => [
                'ebook' => $ebook ? [
                    'id' => $ebook->id,
                    'url' => $ebook->getUrl(),
                    'name' => $ebook->file_name,
                    'size' => $ebook->size,
                    'mime' => $ebook->mime_type,
                ] : null,
                'cover' => $cover ? [
                    'id' => $cover->id,
                    'url' => $cover->getUrl(),
                    'thumb' => $cover->hasGeneratedConversion('thumb') ? $cover->getUrl('thumb') : null,
                    'sm' => $cover->hasGeneratedConversion('sm') ? $cover->getUrl('sm') : null,
                    'md' => $cover->hasGeneratedConversion('md') ? $cover->getUrl('md') : null,
                ] : null,
                'attachments' => $attachments->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'url' => $m->getUrl(),
                        'name' => $m->file_name,
                        'size' => $m->size,
                        'mime' => $m->mime_type,
                    ];
                })->all(),
            ],
        ];
    }
}



