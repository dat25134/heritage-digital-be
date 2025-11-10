<?php declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Topics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('topics', 'slug')->ignore($id)->whereNull('deleted_at')],
            'description' => ['nullable', 'string'],
        ];
    }
}


