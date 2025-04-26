<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Admin\PreferredWorkEnvironmentResource;
use App\Repositories\Contracts\Common\PreferredWorkEnvironmentRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class PreferredWorkEnvironmentController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        return PreferredWorkEnvironmentResource::collection(
            Factory::make(PreferredWorkEnvironmentRepositoryContract::class)->search($request),
        );
    }
}
