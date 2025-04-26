<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Client;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ShortlistRepositoryContract
{
    public function list(Request $request): Collection;
}
