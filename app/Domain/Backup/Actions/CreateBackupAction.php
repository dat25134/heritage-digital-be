<?php

declare(strict_types=1);

namespace App\Domain\Backup\Actions;

use App\Domain\Backup\Models\Backup;
use App\Jobs\CreateBackupJob;

class CreateBackupAction
{
    public function execute(string $type = 'full', ?int $userId = null): Backup
    {
        // Validate type
        if (!in_array($type, ['full', 'database_only', 'files_only'])) {
            throw new \InvalidArgumentException("Invalid backup type: {$type}");
        }

        // Create backup record
        $backup = Backup::create([
            'name' => 'backup-' . now()->format('Y-m-d-H-i-s'),
            'disk' => config('backup.backup.destination.disks')[0] ?? 'local',
            'path' => '',
            'size' => 0,
            'type' => $type,
            'status' => 'pending',
            'created_by' => $userId,
        ]);

        // Dispatch job
        CreateBackupJob::dispatch($backup->id, $type, $userId)->onQueue('backups');

        return $backup;
    }
}

