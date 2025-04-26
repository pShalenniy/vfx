<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\Traits;

use App\Mail\Client\ForgotPassword\ForgotPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

trait ForgotPasswordTrait
{
    protected function sendResetPasswordLink(
        Request $request,
        string $resetPasswordUrlName,
        string $brokerName,
    ): void {
        $broker = Password::broker($brokerName);

        $broker->sendResetLink(
            $request->only(['email']),
            static function ($model, $token) use ($resetPasswordUrlName) {
                $resetPasswordUrl = URL::route($resetPasswordUrlName, [
                    'token' => $token,
                    'email' => $model->getAttribute('email'),
                ]);

                Mail::to($model)->send(new ForgotPasswordMail($resetPasswordUrl));
            },
        );
    }
}
