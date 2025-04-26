<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\Candidate;

use App\Helpers\JsHelper;
use App\Http\Controllers\Client\Traits\ResetPasswordTrait;
use App\Http\Requests\Client\ForgotPassword\SendPasswordRequest;
use App\Http\Requests\Client\ResetPassword\ResetPasswordRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

class ResetPasswordController extends Controller
{
    use ResetPasswordTrait;

    public function view(SendPasswordRequest $request, string $token): ViewContract
    {
        JsHelper::push([
            'token' => $token,
            'email' => $request->get('email'),
        ]);

        return View::make('client.pages.candidate.reset-password');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(
        ResetPasswordRequest $request,
        string $token,
    ): JsonResponse {
        return $this->resetPasswordByBrokerName($request, $token, 'candidates');
    }
}
