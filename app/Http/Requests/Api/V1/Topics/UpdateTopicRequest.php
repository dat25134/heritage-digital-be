<?php declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Topics;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('topics.update') ?? false;
    }

    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:topics,slug,' . $id],
            'description' => ['nullable', 'string'],
        ];
    }
}


