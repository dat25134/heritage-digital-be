<?php

declare(strict_types=1);

namespace App\Http\Resources\Videos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VideoCollection extends ResourceCollection
{
    public $collects = VideoResource::class;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'pagination' => [
                    'total' => $this->resource->total() ?? null,
                    'count' => $this->resource->count() ?? null,
                    'per_page' => $this->resource->perPage() ?? null,
                    'current_page' => $this->resource->currentPage() ?? null,
                    'last_page' => $this->resource->lastPage() ?? null,
                ],
            ],
            'message' => '',
            'errors' => null,
        ];
    }
}


