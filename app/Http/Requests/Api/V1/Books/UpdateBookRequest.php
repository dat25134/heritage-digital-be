<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Books;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('books.update') ?? false;
    }

    public function rules(): array
    {
        // Route model binding may pass a Book model instance for 'book'
        $routeBook = $this->route('book');
        $routeId = $this->route('id');
        $id = null;
        if (is_object($routeBook) && isset($routeBook->id)) {
            $id = (int) $routeBook->id;
        } elseif (!is_null($routeId)) {
            $id = (int) $routeId;
        } elseif (!is_null($routeBook)) {
            // In case route('book') is an ID string/number
            $id = (int) $routeBook;
        } else {
            $id = 0;
        }

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'nullable', 'string', 'max:255', Rule::unique('books', 'slug')->ignore($id)->whereNull('deleted_at')],
            'description' => ['sometimes', 'nullable', 'string'],
            'author' => ['sometimes', 'nullable', 'string', 'max:255'],
            'publisher' => ['sometimes', 'nullable', 'string', 'max:255'],
            'published_year' => ['sometimes', 'nullable', 'integer', 'min:0', 'max:2100'],
            'isbn' => ['sometimes', 'nullable', 'string', 'max:64', Rule::unique('books', 'isbn')->ignore($id)->whereNull('deleted_at')],
            'page_count' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:20000'],
            'status' => ['sometimes', 'nullable', 'in:draft,published,archived'],
            'published_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}



