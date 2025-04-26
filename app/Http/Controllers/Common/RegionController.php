<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\RegionResource;
use App\Models\Country;
use App\Repositories\Contracts\Common\RegionRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RegionController extends Controller
{
    public function listByCountry(Request $request, Country $country): array
    {
        $repository = Factory::make(RegionRepositoryContract::class);

        return [
            'data' => RegionResource::collection(
                $repository->getByCountry($request, $country),
            ),
            'meta' => [
                'count' => $repository->countByCountry($country),
            ],
        ];
    }
}
