<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Client;

use App\Models\Candidate;
use App\Models\Shortlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CandidateRepositoryContract
{
    public function listFromShortlist(
        Shortlist $shortlist,
        array $columns = ['*'],
        array $with = [],
    ): Collection;

    public function paginate(
        Request $request,
        array $columns = ['*'],
        array $with = [],
    ): Collection|LengthAwarePaginator;

    public function getAlternativeTalents(Candidate $candidate): LengthAwarePaginator;
}
