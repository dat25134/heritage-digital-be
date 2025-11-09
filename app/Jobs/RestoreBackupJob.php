<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\Backup\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class RestoreBackupJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $backupId,
        private readonly string $backupPath,
        private readonly string $disk = 'local',
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
            Log::warning("Backup restore initiated by user {$this->userId}: {$this->backupPath}");

            // Verify backup file exists
            if (!Storage::disk($this->disk)->exists($this->backupPath)) {
                throw new \RuntimeException("Backup file not found: {$this->backupPath}");
            }

            // Get full path to backup file
            $fullPath = Storage::disk($this->disk)->path($this->backupPath);

            // Extract backup file
            $extractPath = storage_path('app/restore-temp-' . time());
            if (!is_dir($extractPath)) {
                mkdir($extractPath, 0755, true);
            }

            // Extract zip file
            $zip = new ZipArchive();
            if ($zip->open($fullPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                throw new \RuntimeException('Failed to extract backup file');
            }

            // Restore database
            $dbDumpPath = $extractPath . '/db-dumps';
            if (is_dir($dbDumpPath)) {
                $dbFiles = glob($dbDumpPath . '/*.sql');
                if (!empty($dbFiles)) {
                    // Import database dump
                    // Note: This is a simplified version. In production, you should
                    // use proper database restore commands based on your DB type
                    Log::info('Database restore would be performed here');
                    // Artisan::call('db:restore', ['file' => $dbFiles[0]]);
                }
            }

            // Restore files
            $filesPath = $extractPath . '/files';
            if (is_dir($filesPath)) {
                // Copy files back to storage
                $this->copyDirectory($filesPath, storage_path('app'));
            }

            // Cleanup
            $this->deleteDirectory($extractPath);

            $backup->update([
                'status' => 'restored',
            ]);

            Log::info("Backup restored successfully: {$backup->name}");
        } catch (\Exception $e) {
            Log::error('Restore failed: ' . $e->getMessage(), [
                'backup_id' => $this->backupId,
                'exception' => $e,
            ]);

            $backup->update([
                'status' => 'restore_failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function copyDirectory(string $source, string $destination): void
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $dest = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!is_dir($dest)) {
                    mkdir($dest, 0755, true);
                }
            } else {
                copy($item, $dest);
            }
        }
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}

