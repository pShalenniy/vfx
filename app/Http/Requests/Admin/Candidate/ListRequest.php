<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'starred_candidates' => ['nullable', 'boolean'],
            'keyword' => ['nullable', 'string', 'max:255'],
        ];
    }
}
