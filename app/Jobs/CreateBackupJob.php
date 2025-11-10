<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Domain\Backup\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Tasks\Backup\BackupJob;
use Spatie\Backup\Tasks\Backup\DbDumperFactory;
use Spatie\Backup\Tasks\Backup\FileSelection;

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

            // Set destination disks from config
            $destinationDisks = config('backup.backup.destination.disks', ['local']);
            $backupName = config('backup.backup.name', env('APP_NAME', 'heritage-digital'));
            
            if (empty($destinationDisks)) {
                throw new \RuntimeException('No backup destination disks configured');
            }

            // Create BackupDestination objects for each disk
            $backupDestinations = Collection::make($destinationDisks)
                ->map(fn (string $diskName) => BackupDestination::create($diskName, $backupName));

            $backupJob->setBackupDestinations($backupDestinations);

            // Configure backup based on type
            $sourceFiles = config('backup.backup.source.files', []);
            $sourceDatabases = config('backup.backup.source.databases', []);

            // Validate required tools for database backup
            if ($this->type === 'full' || $this->type === 'database_only') {
                if (!empty($sourceDatabases)) {
                    $this->validateDatabaseBackupTools();
                }
            }

            if ($this->type === 'database_only') {
                // Only backup database - exclude files
                $backupJob->dontBackupFilesystem();
                if (!empty($sourceDatabases)) {
                    $dbDumpers = Collection::make($sourceDatabases)->mapWithKeys(
                        fn (string $dbConnectionName) => [$dbConnectionName => DbDumperFactory::createFromConnection($dbConnectionName)]
                    );
                    $backupJob->setDbDumpers($dbDumpers);
                }
            } elseif ($this->type === 'files_only') {
                // Only backup files - exclude databases
                $backupJob->dontBackupDatabases();
                if (!empty($sourceFiles)) {
                    $fileSelection = FileSelection::create($sourceFiles['include'] ?? [])
                        ->excludeFilesFrom($sourceFiles['exclude'] ?? [])
                        ->shouldFollowLinks($sourceFiles['follow_links'] ?? false)
                        ->shouldIgnoreUnreadableDirs($sourceFiles['ignore_unreadable_directories'] ?? false);
                    $backupJob->setFileSelection($fileSelection);
                }
            } else {
                // Full backup - backup both databases and files
                if (!empty($sourceDatabases)) {
                    $dbDumpers = Collection::make($sourceDatabases)->mapWithKeys(
                        fn (string $dbConnectionName) => [$dbConnectionName => DbDumperFactory::createFromConnection($dbConnectionName)]
                    );
                    $backupJob->setDbDumpers($dbDumpers);
                }
                if (!empty($sourceFiles)) {
                    $fileSelection = FileSelection::create($sourceFiles['include'] ?? [])
                        ->excludeFilesFrom($sourceFiles['exclude'] ?? [])
                        ->shouldFollowLinks($sourceFiles['follow_links'] ?? false)
                        ->shouldIgnoreUnreadableDirs($sourceFiles['ignore_unreadable_directories'] ?? false);
                    $backupJob->setFileSelection($fileSelection);
                }
            }

            // Run backup
            $backupJob->run();

            // Find the latest backup file
            // Backup files are stored in backupName directory (not backup_path)
            $disk = $backup->disk;
            $backupFiles = Storage::disk($disk)->files($backupName);

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

    /**
     * Validate that required database backup tools are available
     */
    private function validateDatabaseBackupTools(): void
    {
        $dbConnection = config('database.default', 'mysql');
        $dbConfig = config("database.connections.{$dbConnection}");

        if (!$dbConfig) {
            throw new \RuntimeException("Database connection '{$dbConnection}' not found in config");
        }

        $driver = $dbConfig['driver'] ?? null;

        // Check for MySQL/MariaDB
        if (in_array($driver, ['mysql', 'mariadb'])) {
            $mysqldumpPath = $this->findExecutable('mysqldump');
            if (!$mysqldumpPath) {
                throw new \RuntimeException(
                    'mysqldump command not found. Please install MySQL client tools. ' .
                    'On Ubuntu/Debian: sudo apt-get install mysql-client ' .
                    'On CentOS/RHEL: sudo yum install mysql'
                );
            }
        }

        // Check for PostgreSQL
        if ($driver === 'pgsql') {
            $pgdumpPath = $this->findExecutable('pg_dump');
            if (!$pgdumpPath) {
                throw new \RuntimeException(
                    'pg_dump command not found. Please install PostgreSQL client tools. ' .
                    'On Ubuntu/Debian: sudo apt-get install postgresql-client ' .
                    'On CentOS/RHEL: sudo yum install postgresql'
                );
            }
        }
    }

    /**
     * Find executable in PATH
     */
    private function findExecutable(string $command): ?string
    {
        // Check if command exists in PATH
        $whichCommand = PHP_OS_FAMILY === 'Windows' ? 'where' : 'which';
        $output = [];
        $returnVar = 0;
        
        @exec("{$whichCommand} {$command} 2>/dev/null", $output, $returnVar);
        
        if ($returnVar === 0 && !empty($output[0])) {
            return trim($output[0]);
        }

        // Also check common paths
        $commonPaths = [
            '/usr/bin',
            '/usr/local/bin',
            '/bin',
            '/usr/sbin',
            '/usr/local/sbin',
        ];

        foreach ($commonPaths as $path) {
            $fullPath = $path . '/' . $command;
            if (file_exists($fullPath) && is_executable($fullPath)) {
                return $fullPath;
            }
        }

        return null;
    }
}

