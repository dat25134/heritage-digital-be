<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Backup\Models\Backup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanOldBackups extends Command
{
    protected $signature = 'backup:clean {--dry-run : Show what would be deleted without actually deleting}';

    protected $description = 'Clean old backups based on retention policy';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info('Cleaning old backups...');

        // Get retention settings from config
        $keepDaily = (int) config('backup.cleanup.default_strategy.keep_all_backups_for_days', 7);
        $keepWeekly = (int) config('backup.cleanup.default_strategy.keep_daily_backups_for_days', 28);
        $keepMonthly = (int) config('backup.cleanup.default_strategy.keep_monthly_backups_for_months', 3);

        $cutoffDaily = now()->subDays($keepDaily);
        $cutoffWeekly = now()->subDays($keepWeekly);
        $cutoffMonthly = now()->subMonths($keepMonthly);

        // Find backups to delete
        $backupsToDelete = Backup::query()
            ->where('status', 'completed')
            ->where(function ($query) use ($cutoffDaily, $cutoffWeekly, $cutoffMonthly) {
                // Delete backups older than daily retention
                $query->where('created_at', '<', $cutoffDaily)
                    // Keep at least one backup per day after daily period
                    ->orWhere(function ($q) use ($cutoffWeekly) {
                        $q->where('created_at', '<', $cutoffWeekly)
                            ->whereRaw('DATE(created_at) NOT IN (
                                SELECT DISTINCT DATE(created_at)
                                FROM backups
                                WHERE created_at >= ?
                                ORDER BY created_at DESC
                                LIMIT 1
                            )', [$cutoffWeekly]);
                    })
                    // Keep at least one backup per month after weekly period
                    ->orWhere(function ($q) use ($cutoffMonthly) {
                        $q->where('created_at', '<', $cutoffMonthly)
                            ->whereRaw('YEAR(created_at) * 100 + MONTH(created_at) NOT IN (
                                SELECT DISTINCT YEAR(created_at) * 100 + MONTH(created_at)
                                FROM backups
                                WHERE created_at >= ?
                                ORDER BY created_at DESC
                                LIMIT 1
                            )', [$cutoffMonthly]);
                    });
            })
            ->get();

        if ($backupsToDelete->isEmpty()) {
            $this->info('No old backups to clean.');
            return Command::SUCCESS;
        }

        $this->info("Found {$backupsToDelete->count()} backups to delete:");

        foreach ($backupsToDelete as $backup) {
            $this->line("  - {$backup->name} ({$backup->created_at->format('Y-m-d H:i:s')})");

            if (!$isDryRun) {
                // Delete file from disk
                if ($backup->path && Storage::disk($backup->disk)->exists($backup->path)) {
                    Storage::disk($backup->disk)->delete($backup->path);
                }

                // Delete record
                $backup->delete();
            }
        }

        if ($isDryRun) {
            $this->warn('Dry run mode - no backups were actually deleted.');
        } else {
            $this->info('Old backups cleaned successfully.');
        }

        return Command::SUCCESS;
    }
}

