<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'should_notify' => ['nullable', 'boolean'],
        ];
    }
}
