<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Admin;

use App\Models\Candidate;
use App\Models\Country;
use App\Models\Skill;
use App\Repositories\Contracts\Admin\CandidateRepositoryContract;
use App\Scopes\OrderByManyToManyRelationScope;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use function array_filter;

class CandidateRepository implements CandidateRepositoryContract
{
    public function paginate(
        Request $request,
        array $with = [],
    ): LengthAwarePaginator {
        return Candidate::query()
            ->select(['candidates.*'])
            ->when($request->boolean('starred_candidates'), static function ($q) {
                $q->join('star_candidates', 'candidates.id', '=', 'star_candidates.candidate_id');
            })
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $keyword = "{$keyword}%";

                $q->where(static function ($q) use ($keyword) {
                    $q->where('first_name', 'like', $keyword)
                        ->orWhere('last_name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword)
                        ->orWhere('budget_of_biggest_show', 'like', $keyword)
                        ->orWhere('phone_number', 'like', $keyword);
                });
            })
            ->when(array_filter($request->get('sort', [])), static function ($q, $sort) {
                if (!isset($sort['by'])) {
                    return null;
                }

                if ('full_name' === $sort['by']) {
                    return $q
                        ->orderBy('first_name', $sort['direction'])
                        ->orderBy('last_name', $sort['direction']);
                }

                if ('nationalities' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderByManyToManyRelationScope::class,
                        new OrderByManyToManyRelationScope(
                            Country::class,
                            'candidate_nationality',
                            'countries',
                            'country_id',
                            $sort['direction'],
                        ),
                    );
                }

                if ('skills' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderByManyToManyRelationScope::class,
                        new OrderByManyToManyRelationScope(
                            Skill::class,
                            'candidate_skill',
                            'skills',
                            'skill_id',
                            $sort['direction'],
                        ),
                    );
                }

                return $q->orderBy($sort['by'], $sort['direction'] ?? 'asc');
            })
            ->with($with)
            ->paginate();
    }
}
