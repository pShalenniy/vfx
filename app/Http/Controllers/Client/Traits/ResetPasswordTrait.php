<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\Traits;

use App\Mail\Client\ResetPassword\ResetPasswordMail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

trait ResetPasswordTrait
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function resetPasswordByBrokerName(
        Request $request,
        string $token,
        string $brokerName,
    ): JsonResponse {
        $broker = Password::broker($brokerName);

        try {
            if (
                ($model = $broker->getUser($request->only(['email']))) &&
                $broker->tokenExists($model, $token)
            ) {
                DB::transaction(static function () use ($model, $broker, $request) {
                    $model->update($request->only(['password']));
                    $broker->deleteToken($model);
                });

                Mail::to($model)->send(new ResetPasswordMail());
            }
        } catch (Exception) {
            throw ValidationException::withMessages([
                'email' => Lang::get('client/reset-password.page.notification.error'),
            ]);
        }

        return new JsonResponse([
            'message' => Lang::get('client/reset-password.page.notification.success'),
        ]);
    }
}
