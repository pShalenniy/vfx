<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use function in_array;

use const null;
use const true;

class HasInactiveSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return new RedirectResponse(URL::route('home.page'));
        }

        if (null !== $user->getAttribute('role_id')) {
            return $next($request);
        }

        if (null === ($subscription = $user->getRelationValue('subscription'))) {
            return $next($request);
        }

        $status = $subscription->getAttribute('status');

        $redirectMap = [
            'home.page' => [
                Subscription::STATUS_ACTIVE,
            ],
            'subscription.inactive' => [
                Subscription::STATUS_PAUSED,
                Subscription::STATUS_PENDING_AGREEMENT,
                Subscription::STATUS_PENDING_DEMO,
            ],
        ];

        foreach ($redirectMap as $route => $statuses) {
            if (in_array($status, $statuses, true)) {
                return new RedirectResponse(URL::route($route));
            }
        }

        return $next($request);
    }
}
