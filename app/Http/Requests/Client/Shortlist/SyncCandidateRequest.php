<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Shortlist;

use App\Models\Candidate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SyncCandidateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'candidates' => ['array'],
            'candidates.*' => [
                'required',
                Rule::exists(Candidate::class, 'id'),
            ],
        ];
    }
}
