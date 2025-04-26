<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\PreferredLocationResource;
use App\Repositories\Contracts\Common\PreferredLocationRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class PreferredLocationController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        return PreferredLocationResource::collection(
            Factory::make(PreferredLocationRepositoryContract::class)->search($request),
        );
    }
}
