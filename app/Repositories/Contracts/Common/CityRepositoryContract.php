<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface CityRepositoryContract
{
    public function countByRegion(Region $region): int;

    public function getByRegion(Request $request, Region $region, array $with = []): Collection;

    public function search(Request $request): Collection;
}
