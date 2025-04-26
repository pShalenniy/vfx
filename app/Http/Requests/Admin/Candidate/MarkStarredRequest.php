<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Candidate;

use App\Validation\Rules\MarkStarredCandidateRule;
use Illuminate\Foundation\Http\FormRequest;

class MarkStarredRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_period' => [
                'required',
                'date_format:Y-m-d',
                new MarkStarredCandidateRule($this->all()),
            ],
            'end_period' => ['required', 'date_format:Y-m-d', 'gte:start_period'],
        ];
    }
}
