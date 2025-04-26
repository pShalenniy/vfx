<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\TelevisionShowResource;
use App\Repositories\Contracts\Common\TelevisionShowRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class TelevisionShowController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        return TelevisionShowResource::collection(
            Factory::make(TelevisionShowRepositoryContract::class)->search($request),
        );
    }
}
