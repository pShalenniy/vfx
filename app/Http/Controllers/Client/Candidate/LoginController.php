<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\Candidate;

use App\Http\Controllers\Client\Traits\LoginTrait;
use App\Http\Requests\Login\LoginRequest;
use App\Models\Candidate;
use Illuminate\Cache\RateLimiter;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use function password_verify;

use const null;

class LoginController extends Controller
{
    use LoginTrait;

    protected int $maxAttempts = 5;

    protected int $decayMinutes = 1;

    public function view(): ViewContract
    {
        return View::make('client.pages.candidate.login');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(
        LoginRequest $request,
        RateLimiter $limiter,
    ): JsonResponse {
        /** @var \App\Models\Candidate $candidate */
        $candidate = Candidate::query()->where('email', $request->get('email'))->first();

        if (null === $candidate) {
            throw ValidationException::withMessages([
                'email' => Lang::get('client/login.message.failed'),
            ]);
        }

        $throttleKey = Str::lower($candidate->getAttribute('email')).'|'.$request->ip();

        $this->checkLoginThrottle($limiter, $throttleKey);

        if (password_verify($request->get('password'), $candidate->getAttribute('password'))) {
            $limiter->clear($throttleKey);

            $token = $candidate->createToken('token');

            return new JsonResponse([
                'token' => $token->plainTextToken,
            ]);
        }

        $limiter->hit($throttleKey, $this->decayMinutes * 60);

        throw ValidationException::withMessages([
            'email' => Lang::get('client/login.message.failed'),
        ]);
    }
}
