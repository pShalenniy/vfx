<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;

class LogoutController extends Controller
{
    public function logout(Request $request): RedirectResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return new RedirectResponse(URL::route('login.view'));
    }
}
