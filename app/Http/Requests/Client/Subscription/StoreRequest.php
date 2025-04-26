<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Subscription;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'seats' => ['required', 'integer'],
            'departments' => ['nullable', 'array'],
            'departments.*' => [
                'nullable',
                'integer',
                Rule::exists(Department::class, 'id'),
            ],
        ];
    }
}
