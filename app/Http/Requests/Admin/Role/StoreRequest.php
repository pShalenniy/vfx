<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Role;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array'],
            'permissions.*' => [
                'required',
                Rule::exists(Permission::class, 'id'),
            ],
        ];
    }
}
