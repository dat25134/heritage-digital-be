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
        $collection = (string) $this->route('collection');
        $mimetypes = $this->allowedMimeTypes($collection);
        $maxKilobytes = $this->maxKilobytes($collection);

        return [
            'files' => ['required', 'array', 'min:1'],
            'files.*' => array_filter([
                'file',
                $mimetypes ? 'mimetypes:' . implode(',', $mimetypes) : null,
                'max:' . $maxKilobytes,
            ]),
            'caption' => ['sometimes', 'string', 'max:255'],
            'credit' => ['sometimes', 'string', 'max:255'],
        ];
    }

    /**
     * @return string[]
     */
    protected function allowedMimeTypes(string $collection): array
    {
        return match ($collection) {
            'videos' => [
                'video/mp4',
                'video/mpeg',
                'video/quicktime',
                'video/webm',
                'video/x-matroska',
            ],
            'audio' => [
                'audio/mpeg',
                'audio/mp3',
                'audio/wav',
                'audio/x-wav',
                'audio/flac',
                'audio/ogg',
                'audio/webm',
            ],
            'documents' | 'books' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.oasis.opendocument.text',
                'text/plain',
                'application/zip',
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/avif',
            ],
            default => [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/avif',
            ],
        };
    }

    protected function maxKilobytes(string $collection): int
    {
        return match ($collection) {
            'videos' => 512000, // ~500MB
            'audio' => 102400,  // ~100MB
            'documents' => 51200, // ~50MB
            default => 20480,    // ~20MB
        };
    }
}



