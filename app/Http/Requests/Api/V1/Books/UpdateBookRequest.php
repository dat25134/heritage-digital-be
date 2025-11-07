<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Books;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('books.update') ?? false;
    }

    public function rules(): array
    {
        $id = (int) ($this->route('id') ?? $this->route('book'));

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', 'unique:books,slug,' . $id],
            'description' => ['sometimes', 'nullable', 'string'],
            'author' => ['sometimes', 'nullable', 'string', 'max:255'],
            'publisher' => ['sometimes', 'nullable', 'string', 'max:255'],
            'published_year' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:2100'],
            'isbn' => ['sometimes', 'nullable', 'string', 'max:64', 'unique:books,isbn,' . $id],
            'page_count' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:20000'],
            'status' => ['sometimes', 'nullable', 'in:draft,published,archived'],
            'published_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}



