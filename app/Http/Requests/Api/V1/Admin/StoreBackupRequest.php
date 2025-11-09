<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('backups.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'in:full,database_only,files_only'],
        ];
    }
}

