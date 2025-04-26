<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\Traits\LoginTrait;
use App\Http\Requests\Login\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Cache\RateLimiter;
use Illuminate\Container\Container;
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
        return View::make('client.pages.login');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function login(
        LoginRequest $request,
        RateLimiter $limiter,
    ): JsonResponse|LoginResource {
        /** @var \App\Models\Contracts\HasActiveSession $user */
        $user = User::query()->where('email', $request->get('email'))->first();

        if (!$user instanceof User) {
            throw ValidationException::withMessages([
                'email' => Lang::get('client/login.message.failed'),
            ]);
        }

        $throttleKey = Str::lower($user->getAttribute('email')).'|'.$request->ip();

        $this->checkLoginThrottle($limiter, $throttleKey);

        if (null === $user->getAttribute('email_verified_at')) {
            return new JsonResponse(
                [
                    'error' => [
                        'key' => 'email_not_verified',
                        'data' => [
                            'hash' => Container::getInstance()
                                ->make('encrypter')
                                ->encryptString($user->getAttribute('email')),
                        ],
                        'message' => Lang::get('client/login.email_verification_request.error'),
                    ],
                ],
                403,
            );
        }

        if (password_verify($request->get('password'), $user->getAttribute('password'))) {
            $limiter->clear($throttleKey);

            $token = $user->createToken('token');

            return new JsonResponse([
                'token' => $token->plainTextToken,
                'user' => new LoginResource($user),
            ]);
        }

        $limiter->hit($throttleKey, $this->decayMinutes * 60);

        throw ValidationException::withMessages([
            'email' => Lang::get('client/login.message.failed'),
        ]);
    }
}
