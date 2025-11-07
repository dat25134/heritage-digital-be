<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions;

use App\Domain\Documents\Models\Document;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class PublishDocumentAction
{
    public function publish(Document $document, ?CarbonInterface $publishedAt = null): Document
    {
        return DB::transaction(function () use ($document, $publishedAt) {
            $document->status = 'published';
            $document->published_at = $publishedAt?->toDateTimeString() ?? now();
            $document->save();
            return $document;
        });
    }

    public function unpublish(Document $document): Document
    {
        return DB::transaction(function () use ($document) {
            $document->status = 'draft';
            $document->published_at = null;
            $document->save();
            return $document;
        });
    }
}


