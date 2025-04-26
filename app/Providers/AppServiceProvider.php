<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Candidate;
use App\Models\User;
use App\Parsers\UrlParsers\VideoIdParser;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Throwable;

use function ltrim;

use const null;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerRequestMacros();

        $this->registerURLMacros();
    }

    public function register(): void
    {
        $this->app->singleton(VideoIdParser::class, VideoIdParser::class);
    }

    protected function registerRequestMacros(): void
    {
        Request::macro('isAdmin', function () {
            $user = $this->user();

            return $user instanceof User && null !== $user->getAttribute('role_id');
        });

        Request::macro('isCandidate', function () {
            return $this->user() instanceof Candidate;
        });

        Request::macro('isUser', function () {
            $user = $this->user();

            return $user instanceof User && null === $user->getAttribute('role_id');
        });
    }

    protected function registerURLMacros(): void
    {
        URL::macro('mix', function (string $file) {
            try {
                return (new Mix())($file);
            } catch (Throwable) {
                return '/'.ltrim($file, '/');
            }
        });
    }
}
