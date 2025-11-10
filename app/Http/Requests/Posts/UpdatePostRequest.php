<?php
declare(strict_types=1);

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('posts.update') === true;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($id)->whereNull('deleted_at')],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content_html' => ['sometimes', 'required', 'string'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:160'],
            'status' => ['nullable', 'in:draft,published'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}


