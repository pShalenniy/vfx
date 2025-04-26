<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\CityResource;
use App\Models\Region;
use App\Repositories\Contracts\Common\CityRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class CityController extends Controller
{
    public function listByRegion(Request $request, Region $region): array
    {
        $repository = Factory::make(CityRepositoryContract::class);

        return [
            'data' => CityResource::collection(
                $repository->getByRegion(
                    $request,
                    $region,
                    ['timezone'],
                ),
            ),
            'meta' => [
                'count' => $repository->countByRegion($region),
            ],
        ];
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        return CityResource::collection(
            Factory::make(CityRepositoryContract::class)->search($request),
        );
    }
}
