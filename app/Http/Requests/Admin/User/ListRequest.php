<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\User;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use McMatters\Helpers\Helpers\ClassHelper;

class ListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keyword' => ['nullable', 'string', 'max:255'],
            'subscription' => ['nullable', 'array'],
            'subscription.contract_signed' => ['nullable', 'boolean'],
            'subscription.status' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Subscription::class, 'STATUS_')),
            ],
            'subscription.period' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Subscription::class, 'PERIOD_')),
            ],
            'subscription.starts_at' => ['nullable', 'array'],
            'subscription.starts_at.*' => ['nullable', 'date_format:Y-m-d'],
            'subscription.ends_at' => ['nullable', 'array'],
            'subscription.ends_at.*' => ['nullable', 'date_format:Y-m-d'],
            'subscription.has_expired' => ['nullable', 'boolean'],
        ];
    }
}
