<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Documents;

use Illuminate\Foundation\Http\FormRequest;

class PublishDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('documents.publish') ?? false;
    }

    public function rules(): array
    {
        return [
            'published_at' => ['nullable', 'date'],
        ];
    }
}


