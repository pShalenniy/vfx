<?php

declare(strict_types=1);

namespace App\Providers;

use AMgrade\SingleRole\Models\Role;
use App\Models\ActiveSession;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\EmailTemplateSetting;
use App\Models\OurPartner;
use App\Models\Region;
use App\Models\Shortlist;
use App\Models\Skill;
use App\Models\Subscription;
use App\Models\Timezone;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->mapBindings();
        $this->mapModels();
        $this->mapPatterns();

        $this->routes(function () {
            Route::middleware(['web', 'cookie_consent'])->group("{$this->app->basePath()}/routes/client.php");

            Route::middleware(['web', 'cookie_consent', 'candidate'])
                ->prefix('candidate')
                ->name('candidate.')
                ->group("{$this->app->basePath()}/routes/candidate.php");

            Route::middleware(['web', 'cookie_consent'])
                ->prefix('common')
                ->name('common.')
                ->group("{$this->app->basePath()}/routes/common.php");

            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group("{$this->app->basePath()}/routes/admin.php");

            Route::view('admin/{any?}', 'admin.app')
                ->middleware(['web', 'auth'])
                ->where('any', '.*');
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for(
            'api',
            static fn (Request $request) => Limit::perMinute(180)->by(
                $request->user()?->getKey() ?: $request->ip(),
            ),
        );
    }

    protected function mapBindings(): void
    {
        Route::bind(
            'candidateSlug',
            static fn ($value) => Candidate::query()->where('slug', $value)->firstOrFail(),
        );
    }

    protected function mapModels(): void
    {
        Route::model('activeSession', ActiveSession::class);
        Route::model('candidate', Candidate::class);
        Route::model('country', Country::class);
        Route::model('emailTemplateSetting', EmailTemplateSetting::class);
        Route::model('ourPartner', OurPartner::class);
        Route::model('region', Region::class);
        Route::model('role', Role::class);
        Route::model('shortlist', Shortlist::class);
        Route::model('skill', Skill::class);
        Route::model('subscription', Subscription::class);
        Route::model('timezone', Timezone::class);
        Route::model('user', User::class);
    }

    protected function mapPatterns(): void
    {
        Route::pattern('activeSession', '[0-7][0-9A-HJKMNP-TV-Z]{25}');
        Route::pattern('candidate', '[0-9]+');
        Route::pattern('country', '[0-9]+');
        Route::pattern('emailTemplateSetting', '[0-9]+');
        Route::pattern('ourPartner', '[0-9]+');
        Route::pattern('region', '[0-9]+');
        Route::pattern('role', '[0-9]+');
        Route::pattern('shortlist', '[0-9]+');
        Route::pattern('skill', '[0-9]+');
        Route::pattern('subscription', '[0-9]+');
        Route::pattern('timezone', '[0-9]+');
        Route::pattern('user', '[0-9]+');
    }
}
