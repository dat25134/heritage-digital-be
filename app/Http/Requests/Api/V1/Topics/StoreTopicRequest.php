<?php declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Topics;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTopicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('topics.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('topics', 'slug')->whereNull('deleted_at')],
            'description' => ['nullable', 'string'],
        ];
    }
}


