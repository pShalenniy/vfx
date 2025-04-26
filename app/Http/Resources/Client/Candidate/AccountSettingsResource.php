<?php

declare(strict_types=1);

namespace App\Http\Resources\Client\Candidate;

use App\Helpers\CandidateHelper;
use App\Http\Resources\Traits\GetCandidateRelationValues;
use App\Http\Resources\Traits\HasCandidatePortfolioUrl;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

use function str_starts_with;

class AccountSettingsResource extends JsonResource
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
            'picture' => $picture
                ? Storage::disk(Candidate::STORAGE_DISK)->url($picture)
                : Candidate::DEFAULT_PICTURE_PATH,
            'first_name' => $this->resource->getAttribute('first_name'),
            'last_name' => $this->resource->getAttribute('last_name'),
            'city' => $this->resource
                ->getRelationValue('city')
                ?->only(['id', 'name', 'region_id', 'longitude', 'latitude']),
            'region' => $this->resource->getRelationValue('region')?->only(['id', 'name', 'country_id']),
            'country' => $this->resource->getRelationValue('country')?->only(['id', 'name', 'code']),
            'company' => $this->resource->getRelationValue('company')?->only(['id', 'name']),
            'television_shows' => $this->getTelevisionShows($this->resource),
            'timezone' => $this->resource->getRelationValue('timezone')?->only(['id', 'name', 'offset']),
            'budget_of_biggest_show' => $this->resource->getAttribute('budget_of_biggest_show'),
            'gross_annual_salary' => $this->resource->getAttribute('gross_annual_salary'),
            'week_rate' => $this->resource->getAttribute('week_rate'),
            'day_rate' => $this->resource->getAttribute('day_rate'),
            'would_like_work_on' => $this->resource->getAttribute('would_like_work_on'),
            'preferred_sectors' => $this->resource
                ->getRelationValue('preferredSectors')
                ?->map(static fn (PreferredSector $preferredSector) => $preferredSector->only(['id', 'name'])),
            'commercial_experience_years' => CandidateHelper::getYearsOfCommercialExperience(
                $this->resource->getAttribute('commercial_experience'),
            ),
            'commercial_experience' => $this->resource->getAttribute('commercial_experience')?->format('Y'),
            'preferred_locations' => $this->resource
                ->getRelationValue('preferredLocations')
                ?->map(static fn (PreferredLocation $preferredLocation) => $preferredLocation->only(['id', 'name'])),
            'salary_rate_currency' => $this->getSalaryRateCurrencyValue(
                $this->resource->getAttribute('salary_rate_currency'),
            ),
            'skills' => $skills[CandidateSkill::TYPE_KEY] ?? [],
            'want_learn_skills' => $skills[CandidateSkill::TYPE_WANT_LEARN] ?? [],
            'want_work_with_tools' => $skills[CandidateSkill::TYPE_WANT_WORK_WITH_TOOLS] ?? [],
            'current_job_roles' => $jobRoles[CandidateJobRole::TYPE_CURRENT] ?? [],
            'desired_job_roles' => $jobRoles[CandidateJobRole::TYPE_DESIRED] ?? [],
            'next_promotion_job_roles' => $jobRoles[CandidateJobRole::TYPE_NEXT_PROMOTION] ?? [],
            'alternative_citizenship_residencies' => $this->resource
                ->getRelationValue('alternativeCitizenshipResidencies')
                ?->map(static fn (Country $country) => $country->only(['id', 'name', 'code'])),
            'phone_number' => $this->getPhoneNumberAttribute(),
            'portfolio_url' => $this->getOriginalPortfolioUrl($this->resource->getAttribute('portfolio_url')),
            'shortfilm_url' => $this->getOriginalPortfolioUrl($this->resource->getAttribute('shortfilm_url')),
            'travel_availability' => $this->resource->getAttribute('travel_availability'),
            'nationalities' => $this->resource
                ->getRelationValue('nationalities')
                ?->map(static fn (Country $country) => $country->only(['id', 'name'])),
            'imdb_link' => $this->resource->getAttribute('imdb_link'),
            'linkedin_link' => $this->resource->getAttribute('linkedin_link'),
            'instagram_link' => $this->resource->getAttribute('instagram_link'),
            'twitter_link' => $this->resource->getAttribute('twitter_link'),
            'next_availability' => $this->resource->getAttribute('next_availability')?->format('Y-m-d'),
            'professional_interest' => $this->resource->getAttribute('professional_interest'),
            'preferred_work_environments' => $this->resource
                ->getRelationValue('preferredWorkEnvironments')
                ?->map(static fn (PreferredWorkEnvironment $workEnvironment) => $workEnvironment->only(['id', 'name'])),
        ];
    }

    protected function getPhoneNumberAttribute(): ?string
    {
        $phone = $this->resource->getAttribute('phone_number');

        if (null === $phone) {
            return null;
        }

        $phone = (string) $phone;

        return str_starts_with($phone, '+') ? $phone : "+{$phone}";
    }
}
