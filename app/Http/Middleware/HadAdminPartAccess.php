<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HadAdminPartAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isAdmin()) {
            throw new HttpException(Response::HTTP_FORBIDDEN, Lang::get('common.permission_denied'));
        }

        return $next($request);
    }
}
