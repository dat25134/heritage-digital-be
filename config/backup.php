<?php

return [

    'backup' => [
        /*
         * The name of this application. You can use this name to monitor
         * the backups.
         */
        'name' => env('APP_NAME', 'heritage-digital'),

        'source' => [
            'databases' => [
                env('DB_CONNECTION', 'mysql'),
            ],

            'files' => [
                /*
                 * The list of directories and files that will be included in the backup.
                 */
                'include' => [
                    base_path('storage/app'),
                ],

                /*
                 * These directories and files will be excluded from the backup.
                 * You can specify individual files or directories.
                 */
                'exclude' => [
                    base_path('storage/app/backups'),
                    base_path('storage/app/temp'),
                    base_path('storage/app/.gitignore'),
                ],

                /*
                 * Determines if symlinks should be followed.
                 */
                'follow_links' => false,

                /*
                 * Determines if it should avoid unreadable directories.
                 */
                'ignore_unreadable_directories' => false,
            ],
        ],

        'destination' => [
            /*
             * The disk names on which the backups will be stored.
             */
            'disks' => [
                env('BACKUP_DISK', 'local'),
            ],

            /*
             * The directory where the backups will be stored.
             */
            'backup_path' => env('BACKUP_PATH', 'backups'),

            /*
             * The prefix for backup filenames.
             */
            'filename_prefix' => env('BACKUP_FILENAME_PREFIX', env('APP_NAME', 'heritage') . '-'),
        ],

        /*
         * The compression algorithm to be used for creating the archive.
         *
         * Out of the box, Laravel backup supports zip, gzip, bzip2 and tar.
         */
        'compression' => env('BACKUP_COMPRESSION', 'zip'),
    ],

    /*
     * You can get notified when specific events occur. Out of the box you can use 'mail' and 'slack'.
     * For Slack you need to install guzzlehttp/guzzle.
     *
     * You can also use your own notification classes, just make sure they are
     * named after or placed in any of the following directories:
     * - app\Notifications
     */
    'notifications' => [
        'notifications' => [
            \Spatie\Backup\Notifications\Notifications\BackupHasFailed::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFound::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\CleanupHasFailed::class => ['mail'],
            \Spatie\Backup\Notifications\Notifications\BackupWasSuccessful::class => [],
            \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFound::class => [],
            \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessful::class => [],
        ],

        /*
         * Here you can specify the notifiable to which the notifications should be sent. The default
         * notifiable will use the variables specified in this config file.
         */
        'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

        'mail' => [
            'to' => env('BACKUP_NOTIFICATION_EMAIL', env('MAIL_FROM_ADDRESS')),
        ],

        'slack' => [
            'webhook_url' => env('BACKUP_SLACK_WEBHOOK_URL', ''),
            'channel' => env('BACKUP_SLACK_CHANNEL', '#backups'),
            'username' => env('BACKUP_SLACK_USERNAME', 'Laravel Backup'),
            'icon' => env('BACKUP_SLACK_ICON', ':warning:'),
        ],
    ],

    /*
     * Here you can specify which backups should be monitored.
     * If a backup does not meet the specified requirements the
     * UnHealthyBackupWasFound event will be fired.
     */
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'heritage-digital'),
            'disks' => [env('BACKUP_DISK', 'local')],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumAgeInDays::class => env('BACKUP_MAX_AGE_DAYS', 1),
                \Spatie\Backup\Tasks\Monitor\HealthChecks\MaximumStorageInMegabytes::class => env('BACKUP_MAX_STORAGE_MB', 5000),
            ],
        ],
    ],

    'cleanup' => [
        /*
         * The strategy that will be used to cleanup old backups. The default strategy
         * will keep all backups for a certain amount of days. After that period only
         * a daily backup will be kept. After that period only weekly backups will
         * be kept and so on.
         *
         * No matter how you configure it the default strategy will never
         * delete the newest backup.
         */
        'strategy' => \Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy::class,

        'default_strategy' => [
            /*
             * The number of days for which backups must be kept.
             */
            'keep_all_backups_for_days' => env('BACKUP_RETENTION_DAILY', 7),

            /*
             * The number of days for which daily backups must be kept.
             */
            'keep_daily_backups_for_days' => env('BACKUP_RETENTION_WEEKLY', 28),

            /*
             * The number of weeks for which one weekly backup must be kept.
             */
            'keep_weekly_backups_for_weeks' => env('BACKUP_RETENTION_MONTHLY', 12),

            /*
             * The number of months for which one monthly backup must be kept.
             */
            'keep_monthly_backups_for_months' => env('BACKUP_RETENTION_MONTHLY', 3),

            /*
             * The number of years for which one yearly backup must be kept.
             */
            'keep_yearly_backups_for_years' => env('BACKUP_RETENTION_YEARLY', 2),

            /*
             * After cleaning up the backups remove the oldest backup until
             * this amount of megabytes has been reached.
             */
            'delete_oldest_backups_when_using_more_megabytes_than' => env('BACKUP_MAX_STORAGE_MB', 5000),
        ],
    ],

];

