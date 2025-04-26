<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\Candidate;

use App\Http\Controllers\Client\Traits\ForgotPasswordTrait;
use App\Http\Requests\Client\ForgotPassword\SendPasswordRequest;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

use const null;

class ForgotPasswordController extends Controller
{
    use ForgotPasswordTrait;

    public function view(): ViewContract
    {
        return View::make('client.pages.candidate.forgot-password');
    }

    public function sendPassword(SendPasswordRequest $request): JsonResponse
    {
        $this->sendResetPasswordLink($request, 'candidate.reset-password.post', 'candidates');

        return new JsonResponse(null, 204);
    }
}
