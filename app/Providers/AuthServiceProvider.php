<?php

declare(strict_types=1);

namespace App\Providers;

use App\Auth\SanctumCookiesGuard;
use App\Models\Shortlist;
use App\Models\Subscription;
use App\Policies\ShortlistPolicy;
use App\Policies\SubscriptionPolicy;
use Illuminate\Auth\RequestGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

use function array_merge;

use const null;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Shortlist::class => ShortlistPolicy::class,
        Subscription::class => SubscriptionPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerSanctumQueryGuard();
    }

    public function register(): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app->make('config');

        $config->set(
            'auth.guards.sanctum-cookies',
            array_merge(
                $config->get('auth.guards.sanctum', []),
                [
                    'driver' => 'sanctum-cookies',
                    'provider' => null,
                ],
            ),
        );
    }

    protected function registerSanctumQueryGuard(): void
    {
        Auth::resolved(function ($auth) {
            $auth->extend('sanctum-cookies', function ($app, $name, array $config) use ($auth) {
                $guard = new RequestGuard(
                    new SanctumCookiesGuard(
                        $auth,
                        $this->app->make('config')->get('sanctum.expiration'),
                        $config['provider'],
                    ),
                    $this->app->make('request'),
                    $auth->createUserProvider($config['provider'] ?? null),
                );

                $this->app->refresh('request', $guard, 'setRequest');

                return $guard;
            });
        });
    }
}
