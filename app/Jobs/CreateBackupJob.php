<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\Backup\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Tasks\Backup\BackupJob;

class CreateBackupJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $backupId,
        private readonly string $type = 'full',
        private readonly ?int $userId = null
    ) {
    }

    public function handle(): void
    {
        $backup = Backup::find($this->backupId);
        if (!$backup) {
            Log::error("Backup not found: {$this->backupId}");
            return;
        }

        try {
            // Update status to running
            $backup->update([
                'status' => 'running',
            ]);

            // Execute backup using Spatie Backup
            $backupJob = new BackupJob();

            // Configure backup based on type
            if ($this->type === 'database_only') {
                // Only backup database - exclude files
                $backupJob->dontBackupFilesystem();
            } elseif ($this->type === 'files_only') {
                // Only backup files - exclude databases
                $backupJob->dontBackupDatabases();
            }

            // Run backup
            $backupJob->run();

            // Find the latest backup file
            $backupPath = config('backup.backup.destination.backup_path', 'backups');
            $disk = $backup->disk;
            $backupFiles = Storage::disk($disk)->files($backupPath);

            if (empty($backupFiles)) {
                throw new \RuntimeException('No backup files were created');
            }

            // Get the most recent backup file
            $latestBackup = collect($backupFiles)
                ->sortByDesc(fn ($file) => Storage::disk($disk)->lastModified($file))
                ->first();

            if ($latestBackup) {
                $backup->update([
                    'name' => basename($latestBackup),
                    'path' => $latestBackup,
                    'size' => Storage::disk($disk)->size($latestBackup),
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                Log::info("Backup completed successfully: {$backup->name}");
            }
        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage(), [
                'backup_id' => $this->backupId,
                'exception' => $e,
            ]);

            $backup->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}

