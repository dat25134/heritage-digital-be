<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Documents;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('documents.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'image_intro_id' => ['nullable', 'integer', 'exists:image_intros,id'],
        ];
    }
}


