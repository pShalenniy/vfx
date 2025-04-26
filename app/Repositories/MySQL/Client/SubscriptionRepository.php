<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Client;

use App\Models\Subscription;
use App\Repositories\Contracts\Client\SubscriptionRepositoryContract;
use Illuminate\Http\Request;

class SubscriptionRepository implements SubscriptionRepositoryContract
{
    public function get(Request $request): ?Subscription
    {
        $user = $request->user()->load([
            'subscription' => static function ($q) {
                $q->with(['departments:id,name']);
            },
        ]);

        return $user?->getRelationValue('subscription');
    }
}
