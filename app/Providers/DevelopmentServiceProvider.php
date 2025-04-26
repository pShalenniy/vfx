<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\AggregateServiceProvider;

class DevelopmentServiceProvider extends AggregateServiceProvider
{
    protected $providers = [
        \Barryvdh\Debugbar\ServiceProvider::class,
        \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
        \NunoMaduro\Collision\Adapters\Laravel\CollisionServiceProvider::class,
        \Termwind\Laravel\TermwindServiceProvider::class,
        \Spatie\LaravelIgnition\IgnitionServiceProvider::class,
    ];

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register(): void
    {
        if (!$this->app->make('config')->get('app.debug')) {
            return;
        }

        parent::register();
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function provides(): array
    {
        if (!$this->app->make('config')->get('app.debug')) {
            return [];
        }

        return parent::provides();
    }
}
