<?php
declare(strict_types=1);

namespace App\Domain\Posts\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \App\Domain\Posts\Models\Post $post */
        $post = $this->resource;

        $cover = $post->getFirstMedia('cover');

        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'content_html' => $post->content_html,
            'status' => $post->status,
            'published_at' => optional($post->published_at)?->toISOString(),
            'seo_title' => $post->seo_title,
            'seo_description' => $post->seo_description,
            'author' => $post->relationLoaded('author') && $post->author ? [
                'id' => $post->author->id,
                'name' => $post->author->name,
            ] : null,
            'cover' => $cover ? [
                'id' => $cover->id,
                'url' => $cover->getUrl(),
                'thumb' => $cover->hasGeneratedConversion('thumb') ? $cover->getUrl('thumb') : null,
                'sm' => $cover->hasGeneratedConversion('sm') ? $cover->getUrl('sm') : null,
                'md' => $cover->hasGeneratedConversion('md') ? $cover->getUrl('md') : null,
                'lg' => $cover->hasGeneratedConversion('lg') ? $cover->getUrl('lg') : null,
            ] : null,
            'gallery' => $post->getMedia('gallery')->map(function ($m) {
                return [
                    'id' => $m->id,
                    'url' => $m->getUrl(),
                    'thumb' => $m->hasGeneratedConversion('thumb') ? $m->getUrl('thumb') : null,
                    'sm' => $m->hasGeneratedConversion('sm') ? $m->getUrl('sm') : null,
                    'md' => $m->hasGeneratedConversion('md') ? $m->getUrl('md') : null,
                    'lg' => $m->hasGeneratedConversion('lg') ? $m->getUrl('lg') : null,
                    'custom_properties' => $m->custom_properties,
                ];
            })->toArray(),
            'created_at' => optional($post->created_at)?->toISOString(),
            'updated_at' => optional($post->updated_at)?->toISOString(),
        ];
    }
}


