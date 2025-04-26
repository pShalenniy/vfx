<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\EmailTemplateSetting;

use App\Models\EmailTemplateSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use McMatters\Helpers\Helpers\ClassHelper;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                Rule::in(ClassHelper::getConstantsStartWith(EmailTemplateSetting::class, 'KEY_')),
            ],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'emails' => ['required', 'array'],
            'emails.*' => ['required', 'email'],
        ];
    }
}
