<?php declare(strict_types=1);

namespace App\Http\Requests\Api\V1\ImageIntros;

use Illuminate\Foundation\Http\FormRequest;

class PublishImageIntroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('image-intros.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}


