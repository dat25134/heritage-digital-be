<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Domain\Books\Models\Book;
use App\Domain\Documents\Models\Document;
use App\Domain\ImageIntros\Models\ImageIntro;
use App\Domain\ResearchPapers\Models\ResearchPaper;
use App\Domain\Videos\Models\Video;
use App\Http\Resources\Api\V1\ImageIntros\ImageIntroResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImageIntroResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_counts_and_links_are_present(): void
    {
        $intro = ImageIntro::query()->create([
            'title' => 'Intro A',
            'slug' => 'intro-a',
            'status' => 'draft',
        ]);

        // Attach related models
        Video::query()->create(['title' => 'v1', 'source_type' => 'external', 'external_url' => 'https://x', 'status' => 'draft', 'image_intro_id' => $intro->id]);
        Document::query()->create(['title' => 'd1', 'status' => 'draft', 'image_intro_id' => $intro->id]);
        Book::query()->create(['title' => 'b1', 'slug' => 'b1', 'status' => 'draft', 'image_intro_id' => $intro->id]);
        ResearchPaper::query()->create(['title' => 'p1', 'slug' => 'p1', 'authors_json' => [], 'year' => 2000, 'status' => 'draft', 'image_intro_id' => $intro->id]);

        $arr = (new ImageIntroResource($intro->fresh()))->toArray(request());

        $this->assertSame(0, $arr['images_count']);
        $this->assertSame(1, $arr['videos_count']);
        $this->assertSame(1, $arr['documents_count']);
        $this->assertSame(1, $arr['books_count']);
        $this->assertSame(1, $arr['papers_count']);

        $this->assertArrayHasKey('links', $arr);
        $this->assertArrayHasKey('videos', $arr['links']);
    }
}


