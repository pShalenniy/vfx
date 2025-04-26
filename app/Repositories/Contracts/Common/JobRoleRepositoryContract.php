<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface JobRoleRepositoryContract
{
    public function search(Request $request): Collection;
}
