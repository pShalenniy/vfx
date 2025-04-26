<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\JobRoleResource;
use App\Repositories\Contracts\Common\JobRoleRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class JobRoleController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        return JobRoleResource::collection(
            Factory::make(JobRoleRepositoryContract::class)->search($request),
        );
    }
}
