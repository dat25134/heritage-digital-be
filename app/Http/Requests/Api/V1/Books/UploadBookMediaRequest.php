<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Books;

use Illuminate\Foundation\Http\FormRequest;

class UploadBookMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('books.upload') ?? false;
    }

    public function rules(): array
    {
        $collection = $this->route('collection');

        $fileRules = ['required', 'file', 'max:102400']; // default 100MB
        if ($collection === 'cover') {
            $fileRules = ['required', 'image', 'mimes:jpeg,png,webp,avif', 'max:5120']; // 5MB
        } elseif ($collection === 'ebook') {
            $fileRules = ['required', 'file', 'mimes:pdf,epub', 'max:102400'];
        } elseif ($collection === 'attachments') {
            $fileRules = ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpeg,png,webp', 'max:51200']; // 50MB
        }

        return [
            'file' => $fileRules,
        ];
    }
}



