<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Books;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('books.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:books,slug'],
            'description' => ['nullable', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'published_year' => ['nullable', 'integer', 'min:0', 'max:2100'],
            'isbn' => ['nullable', 'string', 'max:64', 'unique:books,isbn'],
            'page_count' => ['nullable', 'integer', 'min:1', 'max:20000'],
            'status' => ['nullable', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}



