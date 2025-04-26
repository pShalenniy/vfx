<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Shortlist;
use App\Models\User;

class ShortlistPolicy
{
    public function destroy(User $user, Shortlist $shortlist): bool
    {
        return $user->getKey() === $shortlist->getAttribute('user_id');
    }
}
