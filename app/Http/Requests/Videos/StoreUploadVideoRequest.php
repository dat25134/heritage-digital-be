<?php

declare(strict_types=1);

namespace App\Http\Requests\Videos;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('videos.create') === true;
    }

    public function rules(): array
    {
        $maxSizeMb = (int) (config('media.video_max_mb', 512));
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source_type' => ['required', 'in:upload'],
            'video' => ['required', 'file', 'mimetypes:video/*', 'max:' . ($maxSizeMb * 1024)],
            'status' => ['nullable', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}


