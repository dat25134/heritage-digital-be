<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Media;

use Illuminate\Foundation\Http\FormRequest;

class UploadMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'mimetypes:image/jpeg,image/png,image/webp,image/avif', 'max:20480'],
            'caption' => ['sometimes', 'string', 'max:255'],
            'credit' => ['sometimes', 'string', 'max:255'],
        ];
    }
}



