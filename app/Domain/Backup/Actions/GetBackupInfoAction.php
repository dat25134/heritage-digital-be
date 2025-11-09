<?php

declare(strict_types=1);

namespace App\Domain\Backup\Actions;

use App\Domain\Backup\Models\Backup;
use Illuminate\Support\Facades\Storage;

class GetBackupInfoAction
{
    public function execute(Backup $backup): Backup
    {
        // Update file info if exists
        if ($backup->path && Storage::disk($backup->disk)->exists($backup->path)) {
            $backup->update([
                'size' => Storage::disk($backup->disk)->size($backup->path),
            ]);
        }

        return $backup->fresh();
    }
}

