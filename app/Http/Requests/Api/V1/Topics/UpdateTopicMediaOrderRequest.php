<?php declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Topics;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicMediaOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('topics.order') ?? false;
    }

    public function rules(): array
    {
        return [
            'media_ids' => ['required', 'array', 'min:1'],
            'media_ids.*' => ['integer', 'exists:media,id'],
        ];
    }
}


