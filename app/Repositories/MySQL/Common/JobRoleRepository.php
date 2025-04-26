<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\JobRole;
use App\Repositories\Contracts\Common\JobRoleRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class JobRoleRepository implements JobRoleRepositoryContract
{
    public function search(Request $request): Collection
    {
        return JobRole::query()
            ->select(['id', 'name'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->orderBy('name')
            ->limit(100)
            ->get();
    }
}
