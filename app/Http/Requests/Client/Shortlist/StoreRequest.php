<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Shortlist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('shortlists', 'title')->where('user_id', $this->user()->getKey()),
            ],
        ];
    }
}
