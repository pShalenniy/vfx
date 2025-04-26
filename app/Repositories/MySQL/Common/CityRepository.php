<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\City;
use App\Models\Region;
use App\Repositories\Contracts\Common\CityRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CityRepository implements CityRepositoryContract
{
    public function countByRegion(Region $region): int
    {
        return City::query()->where('region_id', $region->getKey())->count();
    }

    public function getByRegion(Request $request, Region $region, array $with = []): Collection
    {
        return City::query()
            ->select(['id', 'name', 'timezone_id'])
            ->where('region_id', $region->getKey())
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->with($with)
            ->orderBy('name')
            ->limit(100)
            ->get();
    }

    public function search(Request $request): Collection
    {
        return City::query()
            ->select(['id', 'name'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->orderBy('name')
            ->limit(100)
            ->get();
    }
}
