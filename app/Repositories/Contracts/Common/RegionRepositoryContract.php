<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface RegionRepositoryContract
{
    public function countByCountry(Country $country): int;

    public function getByCountry(Request $request, Country $country): Collection;
}
