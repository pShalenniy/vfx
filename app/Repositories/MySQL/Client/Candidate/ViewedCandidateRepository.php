<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Client\Candidate;

use App\Models\Candidate;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\ViewedCandidate;
use App\Repositories\Contracts\Client\Candidate\ViewedCandidateRepositoryContract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ViewedCandidateRepository implements ViewedCandidateRepositoryContract
{
    public function list(Candidate $candidate, array $columns = ['*']): Collection
    {
        $viewedCompaniesQuery = ViewedCandidate::query()
            ->select(['users.company_id'])
            ->join('users', 'users.id', '=', 'viewed_candidates.user_id')
            ->where('candidate_id', $candidate->getKey())
            ->orderByDesc('viewed_candidates.viewed_at');

        return UserCompany::query()
            ->select($columns)
            ->whereIn('id', $viewedCompaniesQuery)
            ->limit(ViewedCandidate::VIEWED_COMPANIES_COUNT)
            ->get();
    }

    public function markViewed(User $user, Candidate $candidate): void
    {
        ViewedCandidate::query()->updateOrCreate(
            [
                'user_id' => $user->getKey(),
                'candidate_id' => $candidate->getKey(),
            ],
            ['viewed_at' => Carbon::now()],
        );
    }
}
