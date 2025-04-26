<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Sentry\State\Scope;

use function Sentry\configureScope;

use const null;

class SetUpLoggers
{
    public function handle(Request $request, Closure $next)
    {
        $this->setUpSentry($request);

        return $next($request);
    }

    protected function setUpSentry(Request $request): void
    {
        configureScope(static function (Scope $scope) use ($request): void {
            /** @var \App\Models\User|null $user */
            if (null !== $user = $request->user()) {
                $scope->setUser([
                    'id' => $user->getKey(),
                    'email' => $user->getAttribute('email'),
                ]);
            }
        });
    }
}
