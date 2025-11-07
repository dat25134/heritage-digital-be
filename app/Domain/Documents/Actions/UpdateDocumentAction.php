<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions;

use App\Domain\Documents\Models\Document;
use Illuminate\Support\Facades\DB;

class UpdateDocumentAction
{
    public function handle(Document $document, array $data, int $userId): Document
    {
        return DB::transaction(function () use ($document, $data, $userId) {
            $document->fill([
                'title' => $data['title'] ?? $document->title,
                'description' => $data['description'] ?? $document->description,
                'status' => $data['status'] ?? $document->status,
                'published_at' => $data['published_at'] ?? $document->published_at,
                'updated_by' => $userId,
            ]);
            $document->save();
            return $document;
        });
    }
}


