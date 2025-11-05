<?php declare(strict_types=1);

namespace App\Http\Requests\Api\V1\ImageIntros;

use Illuminate\Foundation\Http\FormRequest;

class StoreImageIntroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('image-intros.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:image_intros,slug'],
            'summary' => ['nullable', 'string'],
            'content_html' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}


