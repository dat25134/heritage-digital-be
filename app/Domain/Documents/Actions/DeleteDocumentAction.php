<?php

declare(strict_types=1);

namespace App\Domain\Documents\Actions;

use App\Domain\Documents\Models\Document;
use Illuminate\Support\Facades\DB;

class DeleteDocumentAction
{
    public function handle(Document $document): void
    {
        DB::transaction(function () use ($document) {
            $document->delete();
        });
    }
}


