<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE `backups`
                MODIFY `status` ENUM(
                    'pending',
                    'running',
                    'completed',
                    'failed',
                    'restoring',
                    'restored',
                    'restore_failed'
                ) NOT NULL DEFAULT 'pending'
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE `backups`
                MODIFY `status` ENUM(
                    'pending',
                    'running',
                    'completed',
                    'failed'
                ) NOT NULL DEFAULT 'pending'
            ");
        }
    }
};

