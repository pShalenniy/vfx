<?php

declare(strict_types=1);

namespace App\Elasticsearch\Resources;

use App\Helpers\CandidateHelper;
use App\Models\Award;
use App\Models\CandidateIMDBFilmography;
use App\Models\CandidateLinkedinExperience;
use App\Models\CandidateLinkedinExperienceDetail;
use App\Models\Country;
use App\Models\JobRole;
use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\DepartmentJobRole;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Models\Skill;
use App\Models\TelevisionShow;
use Carbon\Carbon;

use function array_column;

use const null;

class CandidateResource extends ElasticsearchResource
{
    public function toArray(): array
    {
        $commercialExperience = (int) $this->resource->getAttribute('commercial_experience')?->format('Y');

        $commercialExperience = $commercialExperience
            ? (int) Carbon::now()->format('Y') - $commercialExperience
            : null;

        $jobRoles = CandidateHelper::getJobRoles($this->resource);

        return [
            'id' => $this->resource->getKey(),
            'first_name' => $this->resource->getAttribute('first_name'),
            'last_name' => $this->resource->getAttribute('last_name'),
            'email' => $this->resource->getAttribute('email'),
            'city' => $this->resource->getRelationValue('city')?->only(['id', 'name', 'region_id']),
            'region' => $this->resource->getRelationValue('region')?->only(['id', 'name', 'country_id']),
            'country' => $this->resource->getRelationValue('country')?->only(['id', 'name']),
            'preferred_sectors' => $this->resource
                ->getRelationValue('preferredSectors')
                ?->map(static fn (PreferredSector $preferredSector) => $preferredSector->only(['id', 'name']))
                ->all(),
            'company' => $this->resource->getRelationValue('company')?->only(['id', 'name']),
            'commercial_experience' => $commercialExperience,
            'television_shows' => $this->resource
                ->getRelationValue('televisionShows')
                ?->map(static fn (TelevisionShow $televisionShow) => $televisionShow->only(['id', 'name', 'imdb_id']))
                ->all(),
            'timezone' => $this->resource->getRelationValue('timezone')?->only(['id', 'name', 'offset']),
            'would_like_work_on' => $this->resource->getAttribute('would_like_work_on'),
            'preferred_locations' => $this->resource
                ->getRelationValue('preferredLocations')
                ?->map(
                    static fn (PreferredLocation $preferredLocation) => $preferredLocation->only([
                        'id',
                        'name',
                    ]),
                )
                ->all(),
            'skills' => $this->resource
                ->getRelationValue('skills')
                ?->map(static function (Skill $skill) {
                    $attributes = $skill->only(['id', 'title']);
                    $attributes += [
                        'level' => $skill->getRelationValue('pivot')->getAttribute('level'),
                    ];

                    return $attributes;
                })
                ->all(),
            'alternative_citizenship_residencies' => $this->resource
                ->getRelationValue('alternativeCitizenshipResidencies')
                ?->only(['id', 'name']),
            'nationalities' => $this->resource
                ->getRelationValue('nationalities')
                ?->map(static fn (Country $country) => $country->only(['id', 'name']))
                ->all(),
            'next_availability' => $this->resource->getAttribute('next_availability')?->format('Y-m-d H:m:s'),
            'current_work' => $this->resource->getAttribute('current_work'),
            'previous_work' => $this->resource->getAttribute('previous_work'),
            'awards' => $this->resource
                ->getRelationValue('awards')
                ?->map(static fn (Award $award) => $award->only(['id', 'name']))
                ->all(),
            'professional_interest' => $this->resource->getAttribute('professional_interest'),
            'departments' => $this->getDepartmentsAttribute(
                $this->resource
                    ->getRelationValue('jobRoles')
                    ?->map(static fn (JobRole $jobRole) => $jobRole->only(['id', 'name']))
                    ->all(),
            ),
            'shortlists' => $this->resource->getRelationValue('shortlists')->pluck('id'),
            'filmographies' => $this->resource
                ->getRelationValue('filmographies')
                ?->map(
                    static fn (CandidateIMDBFilmography $filmography) => $filmography->only([
                        'id',
                        'title',
                        'role',
                        'role_type',
                        'imdb_id',
                    ]),
                )
                ->all(),
            'linkedin_experiences' => $this->resource
                ->getRelationValue('linkedinExperiences')
                ->map(static function (CandidateLinkedinExperience $experience) {
                    $attributes = $experience->only(['id', 'company']);
                    $attributes += [
                        'details' => $experience
                            ->getRelationValue('details')
                            ?->map(
                                static fn (CandidateLinkedinExperienceDetail $detail) => $detail->only([
                                    'id',
                                    'experience_id',
                                    'title',
                                    'description',
                                    'location',
                                ]),
                            )
                            ->all(),
                    ];

                    return $attributes;
                })
                ->all(),
            'current_job_roles' => $jobRoles[CandidateJobRole::TYPE_CURRENT] ?? [],
            'desired_job_roles' => $jobRoles[CandidateJobRole::TYPE_DESIRED] ?? [],
            'next_promotion_job_roles' => $jobRoles[CandidateJobRole::TYPE_NEXT_PROMOTION] ?? [],
            'preferred_work_environments' => $this->resource
                ->getRelationValue('preferredWorkEnvironments')
                ?->map(static fn (PreferredWorkEnvironment $workEnvironment) => $workEnvironment->only(['id', 'name']))
                ->all(),
        ];
    }

    protected function getDepartmentsAttribute(?array $jobRoles): array
    {
        if (empty($jobRoles)) {
            return [];
        }

        $jobRoles = array_column($jobRoles, 'id');

        return DepartmentJobRole::query()
            ->distinct()
            ->select(['department_id'])
            ->whereIn('job_role_id', $jobRoles)
            ->orderBy('department_id')
            ->toBase()
            ->get()
            ->pluck('department_id')
            ->all();
    }
}
