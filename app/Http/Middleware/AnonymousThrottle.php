<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ThrottleRequests;

use const null;

class AnonymousThrottle extends ThrottleRequests
{
    public function handle(
        $request,
        Closure $next,
        $maxAttempts = 30,
        $decayMinutes = 1,
        $prefix = '',
    ) {
        if (null !== $request->user()) {
            return $next($request);
        }

        return parent::handle(
            $request,
            $next,
            $maxAttempts,
            $decayMinutes,
            $prefix,
        );
    }
}
