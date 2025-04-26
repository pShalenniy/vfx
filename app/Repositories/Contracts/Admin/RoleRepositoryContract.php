<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

interface RoleRepositoryContract
{
    public function paginate(Request $request, array $with = []): LengthAwarePaginator;
}
