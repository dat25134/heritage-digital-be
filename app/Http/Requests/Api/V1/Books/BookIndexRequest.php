<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Books;

use Illuminate\Foundation\Http\FormRequest;

class BookIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('books.read') ?? false;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:0', 'max:2100'],
            'isbn' => ['nullable', 'string', 'max:64'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'sort' => ['nullable', 'in:created_at,updated_at,published_at,title'],
            'order' => ['nullable', 'in:asc,desc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}



