<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Admin;

use App\Models\Role;
use App\Repositories\Contracts\Admin\UserRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use function array_filter;

class RoleRepository implements UserRepositoryContract
{
    public function paginate(Request $request, array $with = []): LengthAwarePaginator
    {
        return Role::query()
            ->where('name', '!=', Role::NAME_SUPER_ADMIN)
            ->when(array_filter($request->get('sort', [])), static function ($q, $sort) {
                $q->orderBy($sort['by'] ?? 'id', $sort['direction'] ?? 'asc');
            })
            ->orderBy('name')
            ->with($with)
            ->paginate();
    }
}
