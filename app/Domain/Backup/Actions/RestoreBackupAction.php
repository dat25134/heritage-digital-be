<?php

declare(strict_types=1);

namespace App\Domain\Backup\Actions;

use App\Domain\Backup\Models\Backup;
use App\Jobs\RestoreBackupJob;
use Illuminate\Support\Facades\Storage;

class RestoreBackupAction
{
    public function execute(Backup $backup, string $confirmationToken, ?int $userId = null): Backup
    {
        // Validate backup exists
        if (!Storage::disk($backup->disk)->exists($backup->path)) {
            throw new \RuntimeException('Backup file not found');
        }

        // Validate backup is completed
        if ($backup->status !== 'completed') {
            throw new \RuntimeException('Can only restore completed backups');
        }

        // Validate confirmation token
        $expectedToken = $this->generateConfirmationToken($backup);
        if ($confirmationToken !== $expectedToken) {
            throw new \InvalidArgumentException('Invalid confirmation token');
        }

        // Update status
        $backup->update([
            'status' => 'restoring',
        ]);

        // Dispatch restore job
        RestoreBackupJob::dispatch($backup->id, $backup->path, $backup->disk, $userId)
            ->onQueue('backups');

        return $backup->fresh();
    }

    public function generateConfirmationToken(Backup $backup): string
    {
        // Generate token based on backup ID and date
        return 'RESTORE_BACKUP_' . $backup->id . '_' . $backup->created_at->format('Y-m-d');
    }
}

