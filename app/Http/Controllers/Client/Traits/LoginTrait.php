<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\Traits;

use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

trait LoginTrait
{
    protected function checkLoginThrottle(
        RateLimiter $limiter,
        string $throttleKey,
    ): void {
        if ($limiter->tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = $limiter->availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => [Lang::get('client/login.message.throttle', ['seconds' => $seconds])],
            ]);
        }
    }
}
