<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\CompanyResource;
use App\Repositories\Contracts\Common\CompanyRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class CompanyController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        return CompanyResource::collection(
            Factory::make(CompanyRepositoryContract::class)->search($request),
        );
    }
}
