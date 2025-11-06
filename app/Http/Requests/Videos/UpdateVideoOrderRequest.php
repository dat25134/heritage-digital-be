<?php

declare(strict_types=1);

namespace App\Http\Requests\Videos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVideoOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('videos.order') === true;
    }

    public function rules(): array
    {
        return [
            'orders' => ['required', 'array', 'min:1'],
            'orders.*.id' => ['required', 'integer', 'min:1'],
            'orders.*.sort_order' => ['required', 'integer', 'min:0'],
        ];
    }
}


