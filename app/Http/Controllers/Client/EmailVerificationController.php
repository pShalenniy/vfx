<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Mail\Client\Register\RegisterMail;
use App\Mail\Common\Register\VerifyEmailMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Throwable;

use const null;

class EmailVerificationController extends Controller
{
    public function resend(string $hash): JsonResponse
    {
        try {
            $email = Container::getInstance()->make('encrypter')->decryptString($hash);

            /** @var \App\Models\User $user */
            if (null !== ($user = User::query()->where('email', $email)->first())) {
                Mail::to($user)->send(new VerifyEmailMail($user));
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['controller' => static::class, 'method' => __FUNCTION__]);
        }

        return new JsonResponse(null, 204);
    }

    public function verify(User $user): RedirectResponse
    {
        if (!$user->hasVerifiedEmail()) {
            $data = ['email_verified_at' => Carbon::now()];

            DB::transaction(static function () use ($user, $data) {
                $user->forceFill($data)->save();
            });

            Mail::to($user)->send(new RegisterMail());
        }

        return new RedirectResponse(URL::route('login.view'));
    }
}
