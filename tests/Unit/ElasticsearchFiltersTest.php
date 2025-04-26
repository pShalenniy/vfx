<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Elasticsearch\Filters\AvailabilityFilter;
use App\Elasticsearch\Filters\ElasticsearchQuery;
use App\Elasticsearch\Filters\SkillFilter;
use App\Elasticsearch\Filters\TimezoneFilter;
use App\Models\Candidate;
use App\Models\Shortlist;
use App\Models\Skill;
use App\Models\Timezone;
use Illuminate\Support\Facades\Config;
use McMatters\Helpers\Helpers\ServerHelper;
use Tests\TestCase;
use Tests\Traits\InteractsWithRequest;

use const null;
use const true;

class ElasticsearchFiltersTest extends TestCase
{
    use InteractsWithRequest;

    protected ?string $connection;

    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
    ) {
        parent::__construct($name, $data, $dataName);

        ServerHelper::longProcesses();
    }

    public function testAvailabilityFilter(): void
    {
        $availabilityFrom = Candidate::query()->orderBy('next_availability')->value('next_availability');
        $availabilityTo = Candidate::query()->orderByDesc('next_availability')->value('next_availability');

        $candidatesCount = Candidate::query()
            ->where('next_availability', '>=', $availabilityFrom)
            ->where('next_availability', '<=', $availabilityTo)
            ->count();

        $request = $this->getRequest();

        $request->query->set('next_availability', [$availabilityFrom, $availabilityTo]);

        $esResults = (new ElasticsearchQuery($request))
            ->apply(new AvailabilityFilter())
            ->get([
                'stored_fields' => [],
                'size' => 0,
                'track_total_hits' => true,
            ]);

        $this->assertEquals(
            $candidatesCount,
            $esResults['hits']['total']['value'],
        );
    }

    public function testJobTypeFilter(): void
    {
        $skill = Skill::query()
            ->whereIn(
                'id',
                Candidate::query()
                    ->join('candidate_skill', 'id', '=', 'candidate_skill.candidate_id')
                    ->distinct()
                    ->select('candidate_skill.skill_id'),
            )
            ->inRandomOrder()
            ->first();

        $candidatesCount = Candidate::query()
            ->join('candidate_skill', 'id', '=', 'candidate_skill.candidate_id')
            ->where('candidate_skill.skill_id', $skill->getKey())
            ->count();

        $request = $this->getRequest();
        $request->query->set('skill_id', $skill->getKey());

        $esResults = (new ElasticsearchQuery($request))
            ->apply(new SkillFilter())
            ->get([
                'stored_fields' => [],
                'size' => 0,
                'track_total_hits' => true,
            ]);

        $this->assertEquals(
            $candidatesCount,
            $esResults['hits']['total']['value'],
        );
    }

    public function testTimezoneFilter(): void
    {
        $timezone = Timezone::query()
            ->whereIn(
                'id',
                Candidate::query()
                    ->distinct()
                    ->select('timezone_id')
                    ->whereNotNull('timezone_id'),
            )
            ->inRandomOrder()
            ->first();

        $candidatesCount = Candidate::query()->where('timezone_id', $timezone->getKey())->count();

        $request = $this->getRequest();
        $request->query->set('timezone_id', $timezone->getKey());

        $esResults = (new ElasticsearchQuery($request))
            ->apply(new TimezoneFilter())
            ->get([
                'stored_fields' => [],
                'size' => 0,
                'track_total_hits' => true,
            ]);

        $this->assertEquals(
            $candidatesCount,
            $esResults['hits']['total']['value'],
        );
    }

    public function testShortlistFilter(): void
    {
        $shortlist = Shortlist::query()
            ->join('candidate_shortlist', 'shortlists.id', '=', 'candidate_shortlist.shortlist_id')
            ->whereIn(
                'candidate_shortlist.candidate_id',
                Candidate::query()
                    ->distinct()
                    ->select('id'),
            )
            ->inRandomOrder()
            ->first();

        $candidatesCount = Candidate::query()
            ->join('candidate_shortlist', 'shortlists.id', '=', 'candidate_shortlist.shortlist_id')
            ->where('candidate_shortlist.shortlist_id', $shortlist->getKey())
            ->count();

        $request = $this->getRequest();
        $request->query->set('shortlist', $shortlist->getKey());

        $esResults = (new ElasticsearchQuery($request))
            ->apply(new TimezoneFilter())
            ->get([
                'stored_fields' => [],
                'size' => 0,
                'track_total_hits' => true,
            ]);

        $this->assertEquals(
            $candidatesCount,
            $esResults['hits']['total']['value'],
        );
    }

    protected function setUp(): void
    {
        $this->connection = Config::get('database.default');
        Config::set('database.default', 'mysql');

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Config::set('database.default', $this->connection);
    }
}
