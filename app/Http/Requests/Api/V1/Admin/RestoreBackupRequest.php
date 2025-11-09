<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RestoreBackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('backups.restore') ?? false;
    }

    public function rules(): array
    {
        return [
            'confirmation' => ['required', 'string'],
            'confirm_text' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirmation.required' => 'Confirmation token is required for restore operation.',
        ];
    }
}

