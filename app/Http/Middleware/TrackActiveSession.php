<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\ActiveSession;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

use function str_replace;

use const false;
use const null;

class TrackActiveSession
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\Contracts\HasActiveSession $user */
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

        if (null === $activeSession) {
            $this->createSession($request, $user, $tokenId);

            return $next($request);
        }

        if ($this->shouldSessionBeTracked($activeSession)) {
            $this->updateSession($request, $activeSession);
        }

        return $next($request);
    }

    protected function shouldSessionBeTracked(
        ActiveSession $activeSession,
    ): bool {
        $lastActivityUpdateFrequency = Config::get(
            'active-session.last_activity_update_frequency',
        );

        $lastActivatedAtDiff = (int) $activeSession
            ->getAttribute('last_activated_at')
            ?->diffInMinutes(Carbon::now());

        return $lastActivatedAtDiff >= $lastActivityUpdateFrequency;
    }

    protected function createSession(Request $request, User $user, int $tokenId): ActiveSession
    {
        $attributes = $this->getActiveSessionAttributes($request);

        $attributes['token_id'] = $tokenId;

        return DB::transaction(
            static fn () => $user->activeSessions()->create($attributes),
        );
    }

    protected function updateSession(Request $request, ActiveSession $activeSession): void
    {
        $attributes = $this->getActiveSessionAttributes($request);

        DB::transaction(static function () use ($activeSession, $attributes) {
            $activeSession->update($attributes);
        });
    }

    protected function getActiveSessionAttributes(Request $request): array
    {
        $agent = new Agent();

        $agent->setUserAgent($request->userAgent());
        $agent->setHttpHeaders($request->headers);

        $attributes = [];

        if (null !== ($platform = $agent->platform())) {
            $platformVersion = $agent->version($platform);

            $platformVersion = false !== $platformVersion
                ? str_replace('_', '.', $platformVersion)
                : null;

            $attributes['os'] = null !== $platformVersion
                ? "{$platform} {$platformVersion}"
                : $platform;
        }

        if (null !== ($browser = $agent->browser())) {
            $browserVersion = $agent->version($browser);

            $browserVersion = false !== $browserVersion
                ? str_replace('_', '.', $browserVersion)
                : null;

            $attributes['browser'] = null !== $browserVersion
                ? "{$browser} {$browserVersion}"
                : $browser;
        }

        if (null !== ($ip = $request->ip())) {
            $attributes['ip'] = $ip;
        }

        $attributes['last_activated_at'] = Carbon::now();

        return $attributes;
    }
}
