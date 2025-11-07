<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Research;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResearchPaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('papers.create') ?? false;
    }

    public function rules(): array
    {
        $currentYear = (int) now()->year + 1;
        return [
            'title' => ['required', 'string', 'max:255'],
            'abstract' => ['nullable', 'string'],
            'content_html' => ['nullable', 'string'],
            'authors_json' => ['required', 'array'],
            'authors_json.*.name' => ['required', 'string', 'max:255'],
            'authors_json.*.affiliation' => ['nullable', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.$currentYear],
            'journal' => ['nullable', 'string', 'max:255'],
            'doi' => ['nullable', 'string', 'max:191', Rule::unique('research_papers', 'doi')->whereNotNull('doi')],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
            'slug' => ['nullable', 'string', 'alpha_dash', Rule::unique('research_papers', 'slug')],
            'image_intro_id' => ['nullable', 'integer', 'exists:image_intros,id'],
        ];
    }
}


