<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\Country;
use App\Models\Region;
use App\Repositories\Contracts\Common\RegionRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class RegionRepository implements RegionRepositoryContract
{
    public function countByCountry(Country $country): int
    {
        return Region::query()->where('country_id', $country->getKey())->count();
    }

    public function getByCountry(Request $request, Country $country): Collection
    {
        return Region::query()
            ->select(['id', 'name'])
            ->where('country_id', $country->getKey())
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->orderBy('name')
            ->limit(100)
            ->get();
    }
}
