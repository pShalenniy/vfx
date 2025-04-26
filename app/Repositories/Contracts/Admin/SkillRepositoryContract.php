<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface SkillRepositoryContract
{
    public function paginate(Request $request): LengthAwarePaginator;

    public function search(Request $request): Collection;
}
