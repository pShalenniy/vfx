<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Client;

use App\Elasticsearch\Filters\AvailabilityFilter;
use App\Elasticsearch\Filters\CityFilter;
use App\Elasticsearch\Filters\CommercialExperienceFilter;
use App\Elasticsearch\Filters\CompanyFilter;
use App\Elasticsearch\Filters\CountryFilter;
use App\Elasticsearch\Filters\CurrentJobRoleFilter;
use App\Elasticsearch\Filters\DepartmentFilter;
use App\Elasticsearch\Filters\DesiredJobRoleFilter;
use App\Elasticsearch\Filters\ElasticsearchQuery;
use App\Elasticsearch\Filters\ExcludeIdFilter;
use App\Elasticsearch\Filters\JobRoleFilter;
use App\Elasticsearch\Filters\KeywordFilter;
use App\Elasticsearch\Filters\PreferredLocationFilter;
use App\Elasticsearch\Filters\ShortlistFilter;
use App\Elasticsearch\Filters\SkillFilter;
use App\Elasticsearch\Filters\TelevisionShowFilter;
use App\Elasticsearch\Filters\TimezoneFilter;
use App\Models\Candidate;
use App\Models\Shortlist;
use App\Repositories\Contracts\Client\CandidateRepositoryContract;
use Carbon\Carbon;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as RequestFacade;

use function array_column;
use function array_filter;
use function implode;
use function is_array;
use function max;

use const false;
use const null;
use const true;

class CandidateRepository implements CandidateRepositoryContract
{
    public function listFromShortlist(
        Shortlist $shortlist,
        array $columns = ['*'],
        array $with = [],
    ): Collection {
        return Candidate::query()
            ->select($columns)
            ->join('candidate_shortlist', 'candidates.id', '=', 'candidate_shortlist.candidate_id')
            ->where('candidate_shortlist.shortlist_id', $shortlist->getKey())
            ->orderBy('first_name')
            ->with($with)
            ->get();
    }

    public function paginate(
        Request $request,
        array $columns = ['*'],
        array $with = [],
    ): Collection|LengthAwarePaginator {
        if (!$this->hasFilters($request)) {
            return $this->getStarred($with);
        }

        ['page' => $page, 'size' => $size] = $this->getPaginationData($request);

        --$page;
        $page = max(0, $page);

        $body = [
            'stored_fields' => [], // Don't get _source fields.
            'from' => $page * $size,
            'size' => $size,
        ];

        if ($sorting = $request->get('sort')) {
            if (isset($sorting['by'])) {
                $sorting = [$sorting];
            }

            foreach ($sorting as $sort) {
                $body['sort'][] = [
                    $sort['by'] => [
                        'order' => $sort['direction'] ?? 'asc',
                    ],
                ];
            }
        }

        try {
            $hits = (new ElasticsearchQuery($request))
                ->apply(new AvailabilityFilter())
                ->apply(new CityFilter())
                ->apply(new CompanyFilter())
                ->apply(new CommercialExperienceFilter())
                ->apply(new CountryFilter())
                ->apply(new CurrentJobRoleFilter())
                ->apply(new DesiredJobRoleFilter())
                ->apply(new DepartmentFilter())
                ->apply(new ExcludeIdFilter())
                ->apply(new JobRoleFilter())
                ->apply(new KeywordFilter())
                ->apply(new PreferredLocationFilter())
                ->apply(new ShortlistFilter())
                ->apply(new SkillFilter())
                ->apply(new TelevisionShowFilter())
                ->apply(new TimezoneFilter())
                ->get($body);
        } catch (BadRequest400Exception $e) {
            Log::error($e->getMessage());

            $hits = [];
        }

        $candidates = new Collection();

        if (!empty($hits['hits']['hits'])) {
            $ids = array_column($hits['hits']['hits'], '_id');

            $column = (new Candidate())->getKeyName();

            $candidates = Candidate::query()
                ->whereIn($column, $ids)
                ->orderByRaw("FIELD(`{$column}`, ".implode(', ', $ids).')')
                ->with($with)
                ->get($columns);
        }

        return new LengthAwarePaginator(
            $candidates,
            $hits['hits']['total']['value'] ?? 0,
            $size,
            $page + 1,
        );
    }

    public function getAlternativeTalents(Candidate $candidate): LengthAwarePaginator
    {
        $candidateId = $candidate->getKey();

        $request = new Request([
            'exclude_id' => $candidateId,
            'job_roles' => $candidate->jobRoles()->pluck('id')->all(),
            'size' => Candidate::ALTERNATIVE_TALENT_COUNT,
        ]);

        /** @var \Illuminate\Pagination\LengthAwarePaginator $results */
        $alternativeTalents = $this->paginate($request);

        if (Candidate::ALTERNATIVE_TALENT_COUNT === $count = $alternativeTalents->count()) {
            return $alternativeTalents;
        }

        if (!empty($candidateIds = $alternativeTalents->modelKeys())) {
            $exclude = $candidateIds;
        }

        $exclude[] = $candidateId;

        $request->query->remove('job_roles');

        $request->query->set('size', Candidate::ALTERNATIVE_TALENT_COUNT - $count);
        $request->query->set('next_availability', $candidate->getAttribute('next_availability'));
        $request->query->set('exclude_id', $exclude);

        $candidates = $this->paginate($request);

        $alternativeTalents->setCollection(
            $alternativeTalents->getCollection()->merge($candidates->getCollection()),
        );

        return $alternativeTalents;
    }

    protected function getPaginationData(?Request $request = null): array
    {
        $request ??= RequestFacade::instance();

        $params = $request->only(['page', 'size']);

        $size = 6;

        return [
            'page' => (int) ($params['page'] ?? 1),
            'size' => (int) ($params['size'] ?? $size),
        ];
    }

    protected function hasFilters(Request $request): bool
    {
        $keys = [
            'city_id',
            'company_id',
            'commercial_experience',
            'country_id',
            'current_job_role_id',
            'department',
            'desired_job_role_id',
            'keyword',
            'next_availability',
            'preferred_location_id',
            'shortlist',
            'skills',
            'timezone_id',
            'television_shows',
            'size',
        ];

        foreach ($keys as $key) {
            $data = $request->get($key);

            if (is_array($data) && !empty(array_filter($data))) {
                return true;
            }

            if (null !== $data) {
                return true;
            }
        }

        return false;
    }

    protected function getStarred(array $with): Collection
    {
        $size = 10;

        $currentDate = Carbon::now();

        $starCandidates = Candidate::query()
            ->select(['candidates.*'])
            ->join('star_candidates', 'candidates.id', '=', 'star_candidates.candidate_id')
            ->where('star_candidates.start_period', '<=', $currentDate)
            ->where('star_candidates.end_period', '>=', $currentDate)
            ->limit($size)
            ->with($with)
            ->get();

        if (($candidatesCount = $starCandidates->count()) < $size) {
            $candidates = Candidate::query()
                ->orderByDesc('created_at')
                ->limit($size - $candidatesCount)
                ->with($with)
                ->get();

            $starCandidates = $starCandidates->merge($candidates);
        }

        return $starCandidates;
    }
}
