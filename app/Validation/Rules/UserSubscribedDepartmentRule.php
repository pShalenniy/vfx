<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Helpers\SubscriptionHelper;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

use const false;
use const null;
use const true;

class UserSubscribedDepartmentRule implements Rule
{
    public function __construct(protected ?User $user = null)
    {
    }

    public function passes($attribute, $value): bool
    {
        if (!$value) {
            return true;
        }

        $value = (int) $value;

        /** @var \App\Models\Department $department */
        foreach (SubscriptionHelper::availableDepartments($this->user) as $department) {
            if ($department->getKey() === $value) {
                return true;
            }
        }

        return false;
    }

    public function message(): string
    {
        return Lang::get('validation.user_subscribed_department');
    }
}
