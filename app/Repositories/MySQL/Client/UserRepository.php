<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Client;

use App\Models\User;
use App\Repositories\Contracts\Client\UserRepositoryContract;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryContract
{
    public function getAdminUsers(): Collection
    {
        return User::query()
            ->distinct()
            ->select(['users.id', 'users.email'])
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('roles.name', 'super-admin')
            ->get();
    }
}
