<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Helpers\CandidateHelper;
use App\Http\Resources\Traits\GetCandidateRelationValues;
use App\Http\Resources\Traits\HasCandidatePortfolioUrl;
use App\Models\Award;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Models\TelevisionShow;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CandidateResource extends JsonResource
{
    use GetCandidateRelationValues;
    use HasCandidatePortfolioUrl;

    public function toArray($request): array
    {
        $jobRoles = CandidateHelper::getJobRoles($this->resource);
        $skills = $this->getSkills($this->resource);
        $picture = $this->resource->getAttribute('picture');

        return [
            'id' => $this->resource->getKey(),
            'tinsel_town_id' => $this->resource->getAttribute('tinsel_town_id'),
            'picture' => $picture
                ? Storage::disk(Candidate::STORAGE_DISK)->url($picture)
                : Candidate::DEFAULT_PICTURE_PATH,
            'full_name' => $this->resource->getFullNameAttribute(),
            'first_name' => $this->resource->getAttribute('first_name'),
            'last_name' => $this->resource->getAttribute('last_name'),
            'email' => $this->resource->getAttribute('email'),
            'city' => $this->resource->getRelationValue('city')?->only(['id', 'name', 'region_id']),
            'region' => $this->resource->getRelationValue('region')?->only(['id', 'name', 'country_id']),
            'country' => $this->resource->getRelationValue('country')?->only(['id', 'name']),
            'company' => $this->resource->getRelationValue('company')?->only(['id', 'name']),
            'television_shows' => $this->resource
                ->getRelationValue('televisionShows')
                ?->map(static fn (TelevisionShow $televisionShow) => $televisionShow->only(['id', 'name'])),
            'timezone' => $this->resource->getRelationValue('timezone')?->only(['id', 'name', 'offset']),
            'budget_of_biggest_show' => $this->resource->getAttribute('budget_of_biggest_show'),
            'gross_annual_salary' => $this->resource->getAttribute('gross_annual_salary'),
            'week_rate' => $this->resource->getAttribute('week_rate'),
            'day_rate' => $this->resource->getAttribute('day_rate'),
            'would_like_work_on' => $this->resource->getAttribute('would_like_work_on'),
            'commercial_experience' => $this->resource->getAttribute('commercial_experience')?->format('Y'),
            'preferred_sectors' => $this->resource
                ->getRelationValue('preferredSectors')
                ?->map(static fn (PreferredSector $preferredSector) => $preferredSector->only(['id', 'name'])),
            'preferred_locations' => $this->resource
                ->getRelationValue('preferredLocations')
                ?->map(static fn (PreferredLocation $preferredLocation) => $preferredLocation->only(['id', 'name'])),
            'salary_rate_currency' => $this->resource->getAttribute('salary_rate_currency'),
            'skills' => $skills[CandidateSkill::TYPE_KEY] ?? [],
            'want_learn_skills' => $skills[CandidateSkill::TYPE_WANT_LEARN] ?? [],
            'want_work_with_tools' => $skills[CandidateSkill::TYPE_WANT_WORK_WITH_TOOLS] ?? [],
            'current_job_roles' => $jobRoles[CandidateJobRole::TYPE_CURRENT] ?? [],
            'desired_job_roles' => $jobRoles[CandidateJobRole::TYPE_DESIRED] ?? [],
            'next_promotion_job_roles' => $jobRoles[CandidateJobRole::TYPE_NEXT_PROMOTION] ?? [],
            'alternative_citizenship_residencies' => $this->resource
                ->getRelationValue('alternativeCitizenshipResidencies')
                ?->map(static fn (Country $country) => $country->only(['id', 'name'])),
            'phone_number' => $this->resource->getAttribute('phone_number'),
            'portfolio_url' => $this->getOriginalPortfolioUrl($this->resource->getAttribute('portfolio_url')),
            'shortfilm_url' => $this->getOriginalPortfolioUrl($this->resource->getAttribute('shortfilm_url')),
            'vfx_notes' => $this->resource->getAttribute('vfx_notes'),
            'travel_availability' => $this->resource->getAttribute('travel_availability'),
            'nationalities' => $this->resource
                ->getRelationValue('nationalities')
                ?->map(static fn (Country $country) => $country->only(['id', 'name'])),
            'imdb_link' => $this->resource->getAttribute('imdb_link'),
            'linkedin_link' => $this->resource->getAttribute('linkedin_link'),
            'instagram_link' => $this->resource->getAttribute('instagram_link'),
            'twitter_link' => $this->resource->getAttribute('twitter_link'),
            'next_availability' => $this->resource->getAttribute('next_availability')?->format('Y-m-d'),
            'current_work' => $this->resource->getAttribute('current_work'),
            'previous_work' => $this->resource->getAttribute('previous_work'),
            'awards' => $this->resource
                ->getRelationValue('awards')
                ?->map(static fn (Award $award) => $award->only(['id', 'name'])),
            'professional_interest' => $this->resource->getAttribute('professional_interest'),
            'created_at' => $this->resource->getAttribute('created_at')?->format('d/m/Y H:i:s'),
            'preferred_work_environments' => $this->resource
                ->getRelationValue('preferredWorkEnvironments')
                ?->map(static fn (PreferredWorkEnvironment $workEnvironment) => $workEnvironment->only(['id', 'name'])),
            'skill_circles' => $this->resource->getAttribute('skill_circles'),
        ];
    }
}
