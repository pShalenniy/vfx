<?php

declare(strict_types=1);

namespace App\Console\Commands\Candidate;

use App\Console\Helpers\NullProgressBar;
use App\Helpers\CandidateHelper;
use App\Helpers\ErrorHelper;
use App\Helpers\IMDBHelper;
use App\Helpers\LinkedinHelper;
use App\Helpers\PasswordHelper;
use App\Mail\Admin\Candidate\CreatedMail;
use App\Models\Award;
use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\JobRole;
use App\Models\Pivot\AwardCandidate;
use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\Region;
use App\Models\Skill;
use App\Models\TelevisionShow;
use App\Models\Timezone;
use Carbon\Carbon;
use ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use stdClass;
use Throwable;

use function array_flip;
use function array_key_exists;
use function array_shift;
use function preg_match;
use function trim;

use const false;
use const null;

class CollectFromTinselTown extends Command
{
    protected $signature = 'candidate:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'CollectTinselTown candidates from tinsel-town';

    protected array $preferredLocationIdsCache = [];

    protected array $preferredSectorIdsCache = [];

    protected array $jobRoleIdsCache = [];

    protected array $valuesCache = [
        'awards' => [],
        'cities' => [],
        'companies' => [],
        'countries' => [],
        'regions' => [],
        'skills' => [],
        'timezones' => [],
        'televisionShows' => [],
    ];

    protected array $jobRolesMap = [
        CandidateJobRole::TYPE_DESIRED => 'Desired',
        CandidateJobRole::TYPE_CURRENT => 'Current',
    ];

    protected Carbon $now;

    public function handle(): int
    {
        $chunkSize = 500;

        $this->now = Carbon::now();

        try {
            $query = DB::connection('tinseltown_mysql')->table('users');

            $iterator = (clone $query)
                ->join('user_details', 'users.user_id', '=', 'user_details.user_id')
                ->lazyById($chunkSize, 'users.user_id');
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['command' => static::class]);

            return self::FAILURE;
        }

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar($query->count())
            : new NullProgressBar();

        $progressBar->start();

        ElasticsearchService::useBulk();

        $i = 0;

        foreach ($iterator as $candidateItem) {
            try {
                $candidateData = [
                    'first_name' => $candidateItem->first_name,
                    'last_name' => $candidateItem->last_name,
                    'city_id' => $this->getValueId(
                        $candidateItem->city_id,
                        City::class,
                        'cities',
                    ),
                    'region_id' => $this->getValueId(
                        $candidateItem->state_id,
                        Region::class,
                        'regions',
                    ),
                    'country_id' => $this->getValueId(
                        $candidateItem->country_id,
                        Country::class,
                        'countries',
                    ),
                    'timezone_id' => $this->getValueId(
                        $candidateItem->timezone_id,
                        Timezone::class,
                        'timezones',
                    ),
                    'company_id' => $this->getValueId(
                        $candidateItem->company_id,
                        Company::class,
                        'companies',
                    ),
                    'salary_rate_currency' => $this->getRelationValueId(
                        $candidateItem->currency,
                        'salaryRateCurrency',
                    ),
                    'email' => $candidateItem->email,
                    'budget_of_biggest_show' => $this->getBudgetOfBiggestShow($candidateItem->biggest_show_budget),
                    'phone_number' => $this->getPhoneNumber(
                        $candidateItem->phone_code,
                        $candidateItem->phone,
                    ),
                    'travel_availability' => $candidateItem->flexible_to_travel,
                    'picture' => $candidateItem->profile_picture,
                    'day_rate' => $candidateItem->day_rate,
                    'week_rate' => $candidateItem->weekly_rate,
                    'gross_annual_salary' => $candidateItem->gross_annual_salary,
                    'commercial_experience' => $this->getCommercialExperience(
                        $candidateItem->commercial_experience,
                    ),
                    'would_like_work_on' => $candidateItem->general_interest,
                    'imdb_link' => IMDBHelper::sanitizeLink(
                        $this->getValidatedData(
                            $candidateItem->imdb_profile,
                            IMDBHelper::getRegexValidation(),
                        ),
                    ),
                    'linkedin_link' => $this->getValidatedData(
                        $candidateItem->linkedin_profile,
                        LinkedinHelper::getRegexValidation(),
                    ),
                    'twitter_link' => $this->getValidatedData(
                        $candidateItem->twitter_profile,
                        CandidateHelper::getTwitterRegexValidation(),
                    ),
                    'portfolio_url' => $candidateItem->portfolio_url,
                    'current_work' => $candidateItem->current_position,
                    'professional_interest' => $candidateItem->professional_interest,
                    'next_availability' => Carbon::parse($candidateItem->availability_date),
                ];

                $candidate = Candidate::query()
                    ->where('tinsel_town_id', $candidateItem->user_id)
                    ->where('source', Candidate::SOURCE_TINSEL_TOWN)
                    ->first();

                DB::transaction(function () use ($candidate, $candidateItem, $candidateData) {
                    if (null === $candidate) {
                        $password = PasswordHelper::generate();

                        $candidateData['password'] = $password;
                        $candidateData['tinsel_town_id'] = $candidateItem->user_id;
                        $candidateData['source'] = Candidate::SOURCE_TINSEL_TOWN;

                        /** @var \App\Models\Candidate $candidate */
                        $candidate = Candidate::query()->create($candidateData);

                        Mail::to($candidate)->send(
                            new CreatedMail(
                                $password,
                                $candidate->getAttribute('email'),
                                URL::route('candidate.login.view'),
                            ),
                        );
                    } else {
                        $candidate->update($candidateData);
                    }

                    $this->handleAlternativeCitizenshipResidencies($candidateItem, $candidate);
                    $this->handleAwards($candidateItem, $candidate);
                    $this->handleJobRoles($candidateItem, $candidate);
                    $this->handleNationalities($candidateItem, $candidate);
                    $this->handlePreferredLocations($candidateItem, $candidate);
                    $this->handlePreferredSectors($candidateItem, $candidate);
                    $this->handleSkills($candidateItem, $candidate);
                    $this->handleTelevisionShows($candidateItem, $candidate);

                    ElasticsearchService::update($candidate);
                });

                $i++;

                if ($i === $chunkSize) {
                    ElasticsearchService::processBulk();

                    $i = 0;
                }
            } catch (Throwable $e) {
                if (!ErrorHelper::isDuplicateEntry($e)) {
                    Log::error($e->getMessage(), [
                        'command' => static::class,
                        'id' => $candidateItem->user_id,
                    ]);
                }
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        $this->valuesCache = [];

        return self::SUCCESS;
    }

    protected function getBudgetOfBiggestShow(mixed $budget): ?int
    {
        if (null === $budget) {
            return null;
        }

        $mapping = [
            '$0-1 million' => Candidate::BUDGET_OF_BIGGEST_SHOW_0_25M,
            '$1-5 million' => Candidate::BUDGET_OF_BIGGEST_SHOW_0_25M,
            '$5-10 million' => Candidate::BUDGET_OF_BIGGEST_SHOW_0_25M,
            '$10-25 million' => Candidate::BUDGET_OF_BIGGEST_SHOW_0_25M,
            '$25-50 million' => Candidate::BUDGET_OF_BIGGEST_SHOW_25M_50M,
            '$50-100 million' => Candidate::BUDGET_OF_BIGGEST_SHOW_50M_100M,
            '$100-150 million' => Candidate::BUDGET_OF_BIGGEST_SHOW_100M_150M,
            '$150 million +' => Candidate::BUDGET_OF_BIGGEST_SHOW_GT150M,
        ];

        return $mapping[trim((string) $budget)] ?? null;
    }

    protected function getPhoneNumber(?string $phoneCode, ?string $phoneNumber): ?string
    {
        if (null !== $phoneCode && null !== $phoneNumber) {
            $phoneCode = trim($phoneCode, '+');

            return "{$phoneCode}{$phoneNumber}";
        }

        return $phoneNumber ?? null;
    }

    protected function getCommercialExperience(?int $commercialExperienceYears): ?Carbon
    {
        if (null === $commercialExperienceYears) {
            return null;
        }

        return $this->now->clone()->subYears($commercialExperienceYears);
    }

    protected function handleAlternativeCitizenshipResidencies(
        stdClass $candidateItem,
        Candidate $candidate,
    ): void {
        $countryId = $this->getValueId(
            $candidateItem->other_citizenship_residency,
            Country::class,
            'countries',
        );

        if ($countryId) {
            $candidate->alternativeCitizenshipResidencies()->sync([$countryId]);
        }
    }

    protected function handleAwards(stdClass $candidateItem, Candidate $candidate): void
    {
        $candidateAwards = [];

        $awards = DB::connection('tinseltown_mysql')
            ->table('user_accolades')
            ->selectRaw("
                `user_accolade_id`,
                `user_id`,
                `award_id`,
                `show_id`,
                (
                    CASE
                        WHEN `nominated_won` = 'nominated' THEN ?
                        WHEN `nominated_won` = 'won' THEN ?
                    END
                ) AS `nominated_won`
            ", [AwardCandidate::RESULT_NOMINATED, AwardCandidate::RESULT_WON])
            ->where('user_id', $candidateItem->user_id)
            ->lazyById(100, 'user_accolade_id');

        foreach ($awards as $award) {
            $awardId = $this->getValueId(
                $award->award_id,
                Award::class,
                'awards',
            );

            if (null === $awardId) {
                continue;
            }

            if (isset($candidateAwards[$awardId])) {
                continue;
            }

            $candidateAwards[$awardId] = [
                'television_show_id' => $this->getValueId(
                    $award->show_id,
                    TelevisionShow::class,
                    'televisionShows',
                ),
                'result' => $award->nominated_won,
            ];
        }

        $candidate->awards()->sync($candidateAwards);
    }

    protected function handleJobRoles(stdClass $candidateItem, Candidate $candidate): void
    {
        $currentJobRole = $this->jobRolesMap[CandidateJobRole::TYPE_CURRENT];
        $desiredJobRole = $this->jobRolesMap[CandidateJobRole::TYPE_DESIRED];

        $nextPromotionJobRoleId = $candidateItem->promotion_role_id;
        $currentJobRoleId = $candidateItem->current_role_id;

        $candidateTinselTownId = $candidate->getAttribute('tinsel_town_id');

        if (!$candidate->wasRecentlyCreated) {
            $candidate->jobRoles()->detach();
        }

        $candidate->jobRoles()->attach(
            $this->getJobRolesValueIds($currentJobRole, $candidateTinselTownId),
        );

        $candidate->jobRoles()->attach(
            $this->getJobRolesValueIds($desiredJobRole, $candidateTinselTownId),
        );

        $this->handleJobRole($nextPromotionJobRoleId, CandidateJobRole::TYPE_NEXT_PROMOTION, $candidate);
        $this->handleJobRole($currentJobRoleId, CandidateJobRole::TYPE_CURRENT, $candidate);
    }

    protected function handleJobRole(?int $jobRoleId, int $type, Candidate $candidate): void
    {
        if (null !== ($jobRoleId = $this->getJobRoleIdFromCache($jobRoleId))) {
            $candidate->jobRoles()->attach([$jobRoleId => ['type' => $type]]);
        }
    }

    protected function getJobRolesValueIds(string $type, int $candidateTinselTownId): array
    {
        $jobRoles = DB::connection('tinseltown_mysql')
            ->table('user_roles')
            ->select(['user_id', 'role_id', 'role_type'])
            ->distinct()
            ->where('user_id', $candidateTinselTownId)
            ->where('role_type', $type)
            ->get();

        $jobRoleIds = [];

        $jobRolesMap = array_flip($this->jobRolesMap);

        foreach ($jobRoles as $jobRole) {
            $jobRoleId = $jobRole->role_id;
            $jobRoleType = $jobRolesMap[trim($jobRole->role_type)];

            if (null !== ($jobRoleId = $this->getJobRoleIdFromCache($jobRoleId))) {
                $jobRoleIds[$jobRoleId] = ['type' => $jobRoleType];
            }
        }

        return $jobRoleIds;
    }

    protected function getJobRoleIdFromCache(?int $jobRoleId): ?int
    {
        if (empty($jobRoleId)) {
            return null;
        }

        if (!array_key_exists($jobRoleId, $this->jobRoleIdsCache)) {
            $jobRoleValue = JobRole::query()
                ->where('tinsel_town_id', $jobRoleId)
                ->value('id');

            if ($jobRoleValue) {
                $this->jobRoleIdsCache[$jobRoleId] = JobRole::query()
                    ->where('tinsel_town_id', $jobRoleId)
                    ->value('id');
            }
        }

        return $this->jobRoleIdsCache[$jobRoleId] ?? null;
    }

    protected function handleNationalities(stdClass $candidateItem, Candidate $candidate): void
    {
        $nationality = $candidateItem->nationality;

        $nationalityId = $nationality
            ? Country::query()
                ->where('tinsel_town_id', $nationality)
                ->value('id')
            : null;

        if (null !== $nationalityId) {
            $candidate->nationalities()->sync([$nationalityId]);
        }
    }

    protected function handlePreferredLocations(stdClass $candidateItem, Candidate $candidate): void
    {
        $candidatePreferredLocations = [];

        $preferredLocations = DB::connection('tinseltown_mysql')
            ->table('user_prefer_locations')
            ->select(['user_prefer_location_id', 'user_id', 'prefer_location'])
            ->where('user_id', $candidateItem->user_id)
            ->lazyById(100, 'user_prefer_location_id');

        foreach ($preferredLocations as $preferredLocation) {
            if (null === $preferredLocation->prefer_location) {
                continue;
            }

            $preferredLocationId = $preferredLocation->user_prefer_location_id;
            $preferredLocationName = $preferredLocation->prefer_location;

            if (!array_key_exists($preferredLocationId, $this->preferredLocationIdsCache)) {
                $this->preferredLocationIdsCache[$preferredLocationId] = PreferredLocation::query()
                    ->firstOrCreate(
                        [
                            'tinsel_town_id' => $preferredLocationId,
                        ],
                        [
                            'name' => trim($preferredLocationName),
                        ],
                    )
                    ->getKey();
            }

            if (!isset($this->preferredLocationIdsCache[$preferredLocationId])) {
                continue;
            }

            $preferredLocationId = $this->preferredLocationIdsCache[$preferredLocationId];

            if (isset($candidatePreferredLocations[$preferredLocationId])) {
                continue;
            }

            $candidatePreferredLocations[] = $preferredLocationId;
        }

        $candidate->preferredLocations()->sync($candidatePreferredLocations);
    }

    protected function handlePreferredSectors(stdClass $candidateItem, Candidate $candidate): void
    {
        $candidatePreferredSectors = [];

        $preferredSectors = DB::connection('tinseltown_mysql')
            ->table('user_sectors')
            ->join('sectors', 'user_sectors.sector_id', '=', 'sectors.sector_id')
            ->where('user_id', $candidateItem->user_id)
            ->lazyById(100, 'user_sector_id');

        foreach ($preferredSectors as $preferredSector) {
            if (null === $preferredSector->preferredSector) {
                continue;
            }

            $preferredSectorId = $preferredSector->sector_id;
            $preferredSectorName = $preferredSector->name;

            if (!array_key_exists($preferredSectorId, $this->preferredSectorIdsCache)) {
                $this->preferredSectorIdsCache[$preferredSectorId] = PreferredSector::query()
                    ->where('name', trim($preferredSectorName))
                    ->value('id');
            }

            if (!isset($this->preferredSectorIdsCache[$preferredSectorId])) {
                continue;
            }

            $preferredSectorId = $this->preferredSectorIdsCache[$preferredSectorId];

            if (isset($candidatePreferredSectors[$preferredSectorId])) {
                continue;
            }

            $candidatePreferredSectors[] = $preferredSectorId;
        }

        $candidate->preferredSectors()->sync($candidatePreferredSectors);
    }

    protected function handleSkills(stdClass $candidateItem, Candidate $candidate): void
    {
        $candidateSkills = [];

        $skills = DB::connection('tinseltown_mysql')
            ->table('user_skills')
            ->select(['user_skill_id', 'keyword_id'])
            ->where('user_id', $candidateItem->user_id)
            ->lazyById(100, 'user_skill_id');

        foreach ($skills as $skill) {
            if (null === ($skillId = $this->getValueId($skill->keyword_id, Skill::class, 'skills'))) {
                continue;
            }

            if (isset($candidateSkills[$skillId])) {
                continue;
            }

            $candidateSkills[$skillId] = ['type' => CandidateSkill::TYPE_KEY];
        }

        $keywords = DB::connection('tinseltown_mysql')
            ->table('user_keywords')
            ->select(['user_keyword_id', 'keyword_id'])
            ->where('user_id', $candidateItem->user_id)
            ->lazyById(100, 'user_keyword_id');

        foreach ($keywords as $keyword) {
            $skillId = $this->getValueId($keyword->keyword_id, Skill::class, 'skills');

            if (null === $skillId) {
                continue;
            }

            if (isset($candidateSkills[$skillId])) {
                continue;
            }

            $candidateSkills[$skillId] = ['type' => CandidateSkill::TYPE_KEY];
        }

        $candidate->skills()->sync($candidateSkills);
    }

    protected function handleTelevisionShows(
        stdClass $candidateItem,
        Candidate $candidate,
    ): void {
        $candidateTelevisionShows = $this->getTelevisionShowsMapping();

        $televisionShows = DB::connection('tinseltown_mysql')
            ->table('user_shows')
            ->select(['user_show_id', 'user_id', 'show_id', 'keyword_id'])
            ->where('user_id', $candidateItem->user_id)
            ->lazyById(100, 'user_show_id');

        foreach ($televisionShows as $televisionShow) {
            $showId = $televisionShow->show_id;
            $televisionShowId = $televisionShowsMapping[$showId] ?? null;

            if (null === $televisionShowId) {
                $televisionShowId = $this->getValueId(
                    $showId,
                    TelevisionShow::class,
                    'televisionShows',
                );
            }

            if (isset($candidateTelevisionShows[$televisionShowId])) {
                continue;
            }

            $candidateTelevisionShows[$televisionShowId] = [
                'skill_id' => $this->getValueId(
                    $televisionShow->keyword_id,
                    Skill::class,
                    'skills',
                ),
            ];
        }

        $candidate->televisionShows()->sync($candidateTelevisionShows);
    }

    protected function getRelationValueId(?string $fieldValue, string $field): ?int
    {
        if (!$fieldValue) {
            return null;
        }

        $salaryRateCurrencyMapping = [
            'USD' => Candidate::SALARY_RATE_CURRENCY_USD,
            'CAD' => Candidate::SALARY_RATE_CURRENCY_CAD,
            'EURO' => Candidate::SALARY_RATE_CURRENCY_EURO,
            'GBP' => Candidate::SALARY_RATE_CURRENCY_GBP,
            'FRANC' => Candidate::SALARY_RATE_CURRENCY_FRANC,
            'ROUBLE' => Candidate::SALARY_RATE_CURRENCY_ROUBLE,
            'KRONE' => Candidate::SALARY_RATE_CURRENCY_KRONE,
        ];

        return match ($field) {
            'salaryRateCurrency' => $salaryRateCurrencyMapping[$fieldValue] ?? null,
            default => null,
        };
    }

    protected function getValidatedData(?string $item, string $validationPattern): ?string
    {
        if (null === $item) {
            return null;
        }

        return preg_match($validationPattern, $item) ? $item : null;
    }

    protected function getTelevisionShowsMapping(): array
    {
        static $map;

        if (null === $map) {
            try {
                $televisionShowDuplicates = DB::connection('tinseltown_mysql')
                    ->table('shows')
                    ->select(['name', 'start_year', 'end_year', 'season'])
                    ->groupBy(['name', 'start_year', 'end_year', 'season'])
                    ->havingRaw('COUNT(*) > 1')
                    ->get();

                foreach ($televisionShowDuplicates as $televisionShowDuplicate) {
                    $televisionShowIds = DB::connection('tinseltown_mysql')
                        ->table('shows')
                        ->where('name', $televisionShowDuplicate->start_year)
                        ->where('start_year', $televisionShowDuplicate->start_year)
                        ->where('end_year', $televisionShowDuplicate->end_year)
                        ->where('season', $televisionShowDuplicate->season)
                        ->orderBy('show_id')
                        ->pluck('show_id')
                        ->all();

                    $firstTelevisionShowId = array_shift($televisionShowIds);

                    foreach ($televisionShowIds as $televisionId) {
                        $map[$televisionId] = $firstTelevisionShowId;
                    }
                }
            } catch (Throwable $e) {
                Log::error($e->getMessage(), ['command' => static::class]);
            }
        }

        return $map ?? [];
    }

    protected function getValueId(mixed $id, string $model, string $cacheKey): ?int
    {
        if (empty($id)) {
            return null;
        }

        if (!array_key_exists($id, $this->valuesCache[$cacheKey])) {
            $this->valuesCache[$cacheKey][$id] = $model::query()
                ->where('tinsel_town_id', $id)
                ->value('id');
        }

        return $this->valuesCache[$cacheKey][$id];
    }
}
