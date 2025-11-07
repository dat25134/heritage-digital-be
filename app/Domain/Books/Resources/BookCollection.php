<?php

declare(strict_types=1);

namespace App\Domain\Books\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
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
                    'current_page' => $this->resource->currentPage(),
                    'per_page' => $this->resource->perPage(),
                    'total' => $this->resource->total(),
                    'last_page' => $this->resource->lastPage(),
                ],
            ],
        ];
    }
}



