<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\Books\Models\Book;
use App\Domain\Documents\Models\Document;
use App\Domain\ImageIntros\Models\ImageIntro;
use App\Domain\ResearchPapers\Models\ResearchPaper;
use App\Domain\Videos\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageIntroRelationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_many_relations_work(): void
    {
        $intro = ImageIntro::query()->create([
            'title' => 'Intro B',
            'slug' => 'intro-b',
            'status' => 'draft',
        ]);

        $v = Video::query()->create(['title' => 'v', 'source_type' => 'external', 'external_url' => 'https://example.com', 'status' => 'draft', 'image_intro_id' => $intro->id]);
        $d = Document::query()->create(['title' => 'd', 'status' => 'draft', 'image_intro_id' => $intro->id]);
        $b = Book::query()->create(['title' => 'b', 'slug' => 'b', 'status' => 'draft', 'image_intro_id' => $intro->id]);
        $p = ResearchPaper::query()->create(['title' => 'p', 'slug' => 'p', 'authors_json' => [], 'year' => 2001, 'status' => 'draft', 'image_intro_id' => $intro->id]);

        $this->assertTrue($intro->videos->contains($v));
        $this->assertTrue($intro->documents->contains($d));
        $this->assertTrue($intro->books->contains($b));
        $this->assertTrue($intro->papers->contains($p));

        $this->assertSame($intro->id, $v->image_intro_id);
        $this->assertSame($intro->id, $d->image_intro_id);
        $this->assertSame($intro->id, $b->image_intro_id);
        $this->assertSame($intro->id, $p->image_intro_id);
    }
}


