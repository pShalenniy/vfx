<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

use const null;

class SubscriptionPolicy
{
    public function store(User $user): bool
    {
        return null === $user->subscription()->first(['id']);
    }

    public function requestPause(User $user, Subscription $subscription): bool
    {
        return $subscription->getAttribute('pause_count') < Subscription::PAUSE_COUNT;
    }
}
