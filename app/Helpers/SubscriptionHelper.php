<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use const null;

class SubscriptionHelper
{
    public static function availableDepartments(?User $user = null): array
    {
        $user ??= Auth::user();

        if (!$user instanceof User) {
            return [];
        }

        $userId = $user->getKey();

        static $departments;

        if (!isset($departments[$userId])) {
            $subscription = $user->getRelationValue('subscription');

            $departments[$userId] = $subscription instanceof Subscription
                ? $subscription->getRelationValue('departments')->all()
                : [];
        }

        return $departments[$userId];
    }
}
