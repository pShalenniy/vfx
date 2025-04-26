<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\PreferredLocation;
use App\Repositories\Contracts\Common\PreferredLocationRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PreferredLocationRepository implements PreferredLocationRepositoryContract
{
    public function search(Request $request): Collection
    {
        return PreferredLocation::query()
            ->select(['id', 'name'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->orderBy('name')
            ->limit(100)
            ->get();
    }
}
