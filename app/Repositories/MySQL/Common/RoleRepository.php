<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use AMgrade\SingleRole\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\Common\RoleRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use const null;

class RoleRepository implements RoleRepositoryContract
{
    public function paginate(?User $user = null, array $with = []): LengthAwarePaginator
    {
        return Role::query()
            ->with($with)
            ->paginate();
    }
}
