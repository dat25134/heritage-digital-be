<?php

declare(strict_types=1);

namespace App\Domain\Documents\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cover = $this->getFirstMedia('cover');
        $pdf = $this->getFirstMedia('pdf');
        $attachments = $this->getMedia('attachments');

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
                'pdf' => $pdf ? [
                    'id' => $pdf->id,
                    'url' => $pdf->getUrl(),
                    'name' => $pdf->file_name,
                    'size' => $pdf->size,
                    'mime' => $pdf->mime_type,
                ] : null,
                'cover' => $cover ? [
                    'id' => $cover->id,
                    'url' => $cover->getUrl(),
                    'thumb' => $cover->hasGeneratedConversion('thumb') ? $cover->getUrl('thumb') : null,
                    'sm' => $cover->hasGeneratedConversion('sm') ? $cover->getUrl('sm') : null,
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


