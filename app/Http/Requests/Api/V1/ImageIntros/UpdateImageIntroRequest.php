<?php declare(strict_types=1);

namespace App\Http\Requests\Api\V1\ImageIntros;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateImageIntroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('image-intros.update') ?? false;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'alpha_dash', Rule::unique('image_intros', 'slug')->ignore($id)->whereNull('deleted_at')],
            'summary' => ['nullable', 'string'],
            'content_html' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}


