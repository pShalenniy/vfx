<?php

declare(strict_types=1);

namespace App\Auth;

use Illuminate\Http\Request;
use Laravel\Sanctum\Guard;

use function trim;

class SanctumCookiesGuard extends Guard
{
    public function __invoke(Request $request)
    {
        if (($token = $request->cookie('token')) && empty($request->bearerToken())) {
            $token = trim($token, '"');

            $request->headers->set('Authorization', "Bearer {$token}");
        }

        return parent::__invoke($request);
    }
}
