<?php

declare(strict_types=1);

namespace App\Http\Requests\Videos;

use Illuminate\Foundation\Http\FormRequest;

class StoreExternalVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('videos.create') === true;
    }

    public function rules(): array
    {
        $maxSizeMb = (int) (config('media.thumbnail_max_mb', 10));
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source_type' => ['required', 'in:external'],
            'external_url' => ['required', 'url'],
            'thumbnail' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:' . ($maxSizeMb * 1024)],
            'status' => ['nullable', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}


