<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions;

use App\Domain\Documents\Models\Document;
use Illuminate\Support\Facades\DB;

class CreateDocumentAction
{
    public function handle(array $data, int $userId): Document
    {
        return DB::transaction(function () use ($data, $userId) {
            $document = new Document();
            $document->fill([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'published_at' => $data['published_at'] ?? null,
                'created_by' => $userId,
                'updated_by' => $userId,
                'image_intro_id' => $data['image_intro_id'] ?? null,
            ]);
            $document->save();
            return $document;
        });
    }
}


