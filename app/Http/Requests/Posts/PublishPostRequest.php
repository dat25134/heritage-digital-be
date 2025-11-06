<?php
declare(strict_types=1);

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class PublishPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('posts.publish') === true;
    }

    public function rules(): array
    {
        return [
            'publish' => ['required', 'boolean'],
        ];
    }
}


