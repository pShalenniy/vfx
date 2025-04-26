<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Resources\RoleResource;
use App\Repositories\Contracts\Common\RoleRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    public function roles(Request $request): AnonymousResourceCollection
    {
        return RoleResource::collection(
            Factory::make(RoleRepositoryContract::class)->paginate(
                $request->user(),
                ['permissions'],
            ),
        );
    }
}
