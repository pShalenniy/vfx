<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\ForgotPassword;

use Illuminate\Foundation\Http\FormRequest;

class SendPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
