<?php

declare(strict_types=1);

namespace App\Domain\Backup\Actions;

use App\Domain\Backup\Models\Backup;
use Illuminate\Support\Facades\Storage;

class DeleteBackupAction
{
    public function execute(Backup $backup): bool
    {
        // Prevent deletion of running backups
        if ($backup->status === 'running') {
            throw new \RuntimeException('Cannot delete a backup that is currently running');
        }

        // Delete file from disk
        if ($backup->path && Storage::disk($backup->disk)->exists($backup->path)) {
            Storage::disk($backup->disk)->delete($backup->path);
        }

        // Delete record
        return $backup->delete();
    }
}

