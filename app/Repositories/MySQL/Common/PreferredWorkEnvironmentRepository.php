<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\PreferredWorkEnvironment;
use App\Repositories\Contracts\Common\PreferredWorkEnvironmentRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PreferredWorkEnvironmentRepository implements PreferredWorkEnvironmentRepositoryContract
{
    public function search(Request $request): Collection
    {
        return PreferredWorkEnvironment::query()
            ->select(['id', 'name'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->orderBy('name')
            ->limit(100)
            ->get();
    }
}
