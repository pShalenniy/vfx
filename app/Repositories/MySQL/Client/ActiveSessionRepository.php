<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Client;

use App\Models\Contracts\HasActiveSession;
use App\Repositories\Contracts\Client\ActiveSessionRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\Contracts\HasApiTokens;

class ActiveSessionRepository implements ActiveSessionRepositoryContract
{
    public function list(HasActiveSession&HasApiTokens $user): Collection
    {
        return $user->activeSessions()
            ->where('token_id', '!=', $user->currentAccessToken()->getKey())
            ->get();
    }
}
