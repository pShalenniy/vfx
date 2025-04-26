<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Client\Candidate;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ViewedCandidateRepositoryContract
{
    public function list(Candidate $candidate, array $columns = ['*']): Collection;

    public function markViewed(User $user, Candidate $candidate): void;
}
