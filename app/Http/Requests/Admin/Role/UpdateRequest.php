<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Role;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignoreModel($this->route('role')),
            ],
            'permissions' => ['required', 'array'],
            'permissions.*' => [
                'required',
                Rule::exists(Permission::class, 'id'),
            ],
        ];
    }
}
