<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\ContactUs;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255'],
            'telephone_number' => ['required', 'min:3'],
            'enquiry' => ['required', 'string', 'min:3'],
        ];
    }
}
