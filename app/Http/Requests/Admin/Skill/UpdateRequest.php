<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Skill;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('skills')->ignoreModel($this->route('skill')),
            ],
        ];
    }
}
