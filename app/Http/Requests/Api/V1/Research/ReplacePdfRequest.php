<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Research;

use Illuminate\Foundation\Http\FormRequest;

class ReplacePdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('papers.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ];
    }
}


