<?php
declare(strict_types=1);

namespace Tests\Feature\Api\V1\Posts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/posts');
        $response->assertStatus(401);
    }
}


