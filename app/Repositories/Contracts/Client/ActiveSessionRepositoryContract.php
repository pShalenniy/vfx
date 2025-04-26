<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Client;

use App\Models\Contracts\HasActiveSession;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\Contracts\HasApiTokens;

interface ActiveSessionRepositoryContract
{
    public function list(HasActiveSession&HasApiTokens $user): Collection;
}
