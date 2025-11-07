<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Documents;

use Illuminate\Foundation\Http\FormRequest;

class DocumentIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('documents.read') ?? false;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'image_intro_id' => ['nullable', 'integer', 'exists:image_intros,id'],
            'published_from' => ['nullable', 'date'],
            'published_to' => ['nullable', 'date', 'after_or_equal:published_from'],
            'created_from' => ['nullable', 'date'],
            'created_to' => ['nullable', 'date', 'after_or_equal:created_from'],
            'with_trashed' => ['nullable', 'boolean'],
            'only_trashed' => ['nullable', 'boolean'],
            'sort' => ['nullable', 'in:created_at,published_at,title'],
            'order' => ['nullable', 'in:asc,desc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $routeIntroId = $this->route('id') ?? $this->route('imageIntroId');
        if ($routeIntroId !== null && !$this->has('image_intro_id')) {
            $this->merge(['image_intro_id' => (int) $routeIntroId]);
        }
    }
}


