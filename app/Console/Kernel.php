<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        // Monthly backup on 1st of month at 4 AM
        $schedule->call(function () {
            (new \App\Domain\Backup\Actions\CreateBackupAction())->execute('full', null);
        })
            ->monthlyOn(1, '04:00')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Illuminate\Support\Facades\Log::error('Monthly backup job failed');
            });

        // Cleanup old backups (retention policy)
        $schedule->command('backup:clean')
            ->daily()
            ->at('05:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
