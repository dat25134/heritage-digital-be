<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BackfillImageIntroId extends Command
{
    protected $signature = 'intro:backfill {entity : videos|documents|books|papers} {introId : target image_intro id} {--dry-run}';

    protected $description = 'Backfill image_intro_id for legacy records (null only)';

    public function handle(): int
    {
        $entity = (string) $this->argument('entity');
        $introId = (int) $this->argument('introId');
        $dry = (bool) $this->option('dry-run');

        $map = [
            'videos' => \App\Domain\Videos\Models\Video::class,
            'documents' => \App\Domain\Documents\Models\Document::class,
            'books' => \App\Domain\Books\Models\Book::class,
            'papers' => \App\Domain\ResearchPapers\Models\ResearchPaper::class,
        ];

        $modelClass = $map[$entity] ?? null;
        if ($modelClass === null) {
            $this->error('Unsupported entity: ' . $entity);
            return self::FAILURE;
        }

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = new $modelClass();
        $query = $model::query()->whereNull('image_intro_id');
        $count = (int) $query->count();
        if ($count === 0) {
            $this->info('No records to backfill.');
            return self::SUCCESS;
        }

        $this->info("Found {$count} {$entity} with null image_intro_id. Target intro: {$introId}. Dry-run: " . ($dry ? 'yes' : 'no'));

        if ($dry) {
            return self::SUCCESS;
        }

        $updated = $model::query()->whereNull('image_intro_id')->update(['image_intro_id' => $introId]);
        $this->info("Updated {$updated} rows.");
        return self::SUCCESS;
    }
}


