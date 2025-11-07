<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Documents;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('documents.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'nullable', 'in:draft,published,archived'],
            'published_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}


