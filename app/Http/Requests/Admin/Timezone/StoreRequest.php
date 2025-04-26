<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Timezone;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:5'],
            'name' => ['required', 'string', 'max:255'],
            'offset' => ['required', 'string', 'max:255'],
        ];
    }
}
