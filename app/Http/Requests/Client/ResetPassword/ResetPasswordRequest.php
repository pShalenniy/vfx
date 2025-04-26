<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'password' => ['required', Password::min(6)->numbers()->letters()],
            'password_confirmation' => ['required'],
        ];
    }
}
