<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ActiveSession;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

use const null;

class CheckActiveSessionsCount
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\Contracts\HasActiveSession&\Laravel\Sanctum\Contracts\HasApiTokens $user */
        $user = $request->user();

        if (null === $user?->getAttribute('email_verified_at')) {
            return $next($request);
        }

        $tokenId = $user?->currentAccessToken()?->getKey();

        if (null === $tokenId) {
            return $next($request);
        }

        /** @var \App\Models\ActiveSession|null $activeSession */
        $activeSession = ActiveSession::query()
            ->where('token_id', $tokenId)
            ->first();

        $activeSessionsCount = $user?->activeSessions()?->count();
        $allowCount = (int) Config::get('active-session.allow_count', 2);
        $routeName = $request->route()?->getName();
        $activeSessionRouteName = 'active-session.view';

        if (
            $activeSessionsCount < $allowCount &&
            $routeName === $activeSessionRouteName
        ) {
            return new RedirectResponse(URL::route('candidate.page.list'));
        }

        if (
            null === $activeSession &&
            $activeSessionsCount >= $allowCount &&
            $routeName !== $activeSessionRouteName
        ) {
            return new RedirectResponse(URL::route($activeSessionRouteName));
        }

        return $next($request);
    }
}
