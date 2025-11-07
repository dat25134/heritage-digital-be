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

        $cover = $paper->getFirstMedia('cover');
        $pdf = $paper->getFirstMedia('pdf');
        $attachments = $paper->getMedia('attachments');

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

            'pdf_url' => $pdf?->getUrl(),
            'cover_urls' => $cover ? [
                'original' => $cover->getUrl(),
                'thumb' => $cover->getUrl('thumb'),
                'sm' => $cover->getUrl('sm'),
                'md' => $cover->getUrl('md'),
                'lg' => $cover->getUrl('lg'),
            ] : null,
            'attachments' => $attachments->map(fn ($m) => [
                'id' => $m->id,
                'name' => $m->file_name,
                'url' => $m->getUrl(),
                'size' => $m->size,
                'mime' => $m->mime_type,
                'custom_properties' => $m->custom_properties,
            ])->all(),
        ];
    }
}


