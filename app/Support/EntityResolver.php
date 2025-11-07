<?php
declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class EntityResolver
{
    /**
     * @return Model
     */
    public function resolve(string $entity, int $id): Model
    {
        $map = [
            'image-intros' => \App\Domain\ImageIntros\Models\ImageIntro::class,
            'topics' => \App\Domain\Topics\Models\Topic::class,
            'posts' => \App\Domain\Posts\Models\Post::class,
            'research-papers' => \App\Domain\ResearchPapers\Models\ResearchPaper::class,
            'videos' => \App\Domain\Videos\Models\Video::class,
            'documents' => \App\Domain\Documents\Models\Document::class,
            'books' => \App\Domain\Books\Models\Book::class,
        ];

        $modelClass = $map[$entity] ?? null;
        if ($modelClass === null) {
            throw new InvalidArgumentException('Unsupported entity: ' . $entity);
        }

        /** @var Model $model */
        $model = $modelClass::query()->findOrFail($id);
        return $model;
    }
}



