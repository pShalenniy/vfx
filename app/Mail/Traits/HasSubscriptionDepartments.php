<?php

declare(strict_types=1);

namespace App\Mail\Traits;

use App\Models\Subscription;

trait HasSubscriptionDepartments
{
    protected function getDepartments(Subscription $subscription): ?string
    {
        return $subscription->getRelationValue('departments')->implode('name', ', ');
    }
}
