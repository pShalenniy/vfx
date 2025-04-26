<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use const null;

interface RoleRepositoryContract
{
    public function paginate(?User $user = null, array $with = []): LengthAwarePaginator;
}
