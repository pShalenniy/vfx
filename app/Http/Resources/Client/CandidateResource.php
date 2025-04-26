<?php

declare(strict_types=1);

namespace App\Http\Resources\Client;

use App\Helpers\CandidateHelper;
use App\Helpers\VideoIdParserHelper;
use App\Http\Resources\Traits\GetCandidateRelationValues;
use App\Models\Candidate;
use App\Models\CandidateLinkedinExperience;
use App\Models\Country;
use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Parsers\UrlParsers\VideoIdParser;
use Illuminate\Container\Container;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use function array_merge;
use function array_unique;
use function file_exists;
use function implode;
use function mb_strlen;
use function mb_substr;
use function sprintf;

use const null;

class CandidateResource extends JsonResource
{
    use GetCandidateRelationValues;

    public function toArray($request): array
    {
        $id = $this->resource->getKey();
        $jobRoles = CandidateHelper::getJobRoles($this->resource);
        $skills = $this->getSkills($this->resource);
        $picture = $this->resource->getAttribute('picture');
        $country = $this->resource->getRelationValue('country')?->only(['id', 'name', 'code']);
        $alternativeCitizenshipResidencies = $this->resource
            ->getRelationValue('alternativeCitizenshipResidencies')
            ?->map(static fn (Country $country) => $country->only(['id', 'name', 'code']))
            ?->all();

        return [
            'id' => $id,
            'picture' => $picture
                ? Storage::disk(Candidate::STORAGE_DISK)->url($picture)
                : Candidate::DEFAULT_PICTURE_PATH,
            'full_name' => $this->resource->getFullNameAttribute(),
            'email' => $this->resource->getAttribute('email'),
            'first_name' => $this->resource->getAttribute('first_name'),
            'last_name' => $this->resource->getAttribute('last_name'),
            'city' => $this->resource
                ->getRelationValue('city')
                ?->only(['id', 'name', 'region_id', 'longitude', 'latitude']),
            'region' => $this->resource
                ->getRelationValue('region')
                ?->only(['id', 'name', 'country_id']),
            'country' => $country,
            'company' => $this->resource->getRelationValue('company')?->only(['id', 'name']),
            'television_shows' => $this->getTelevisionShows($this->resource),
            'timezone' => $this->resource->getRelationValue('timezone')?->only(['id', 'name', 'offset']),
            'budget_of_biggest_show' => $this->getBudgetOfBiggestShowValue(
                $this->resource->getAttribute('budget_of_biggest_show'),
            ),
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
            'alternative_citizenship_residencies' => $alternativeCitizenshipResidencies,
            'phone_number' => $this->resource->getAttribute('phone_number'),
            'portfolio_url' => $this->getPortfolioIframeCode($this->resource->getAttribute('portfolio_url')),
            'shortfilm_url' => $this->getPortfolioIframeCode($this->resource->getAttribute('shortfilm_url')),
            'travel_availability' => $this->resource->getAttribute('travel_availability'),
            'nationalities' => $this->resource
                ->getRelationValue('nationalities')
                ?->map(static fn (Country $country) => $country->only(['id', 'name'])),
            'imdb_link' => $this->resource->getAttribute('imdb_link'),
            'linkedin_link' => $this->resource->getAttribute('linkedin_link'),
            'instagram_link' => $this->resource->getAttribute('instagram_link'),
            'twitter_link' => $this->resource->getAttribute('twitter_link'),
            'next_availability' => $this->resource->getAttribute('next_availability')?->format('Y-m-d'),
            'available' => $this->resource->getAttribute('next_availability')?->format('d F Y'),
            'current_work' => $this->resource->getAttribute('current_work'),
            'previous_work' => $this->resource->getAttribute('previous_work'),
            'awards' => $this->getAwards($this->resource),
            'professional_interest' => $this->resource->getAttribute('professional_interest'),
            'filmographies' => $this->resource->getRelationValue('filmographies'),
            'linkedin_experiences' => $this->resource
                ->getRelationValue('linkedinExperiences')
                ->map(static function (CandidateLinkedinExperience $candidateLinkedinExperience) {
                    $attributes = $candidateLinkedinExperience->only(['id', 'company', 'image', 'working_period']);

                    $attributes += [
                        'details' => $candidateLinkedinExperience->getRelationValue('details'),
                    ];

                    return $attributes;
                }),
            'download_cv_link' => URL::route('candidate.download-cv', ['candidate' => $id]),
            'slug' => $this->resource->getAttribute('slug'),
            'preferred_work_environments' => $this->resource
                ->getRelationValue('preferredWorkEnvironments')
                ?->map(static fn (PreferredWorkEnvironment $workEnvironment) => $workEnvironment->only(['id', 'name'])),
            'skill_circles' => $this->resource->getAttribute('skill_circles'),
            'vfx_notes' => $this->resource->getAttribute('vfx_notes'),
            'country_flags' => $this->getCountryFlagsAttribute($country, $alternativeCitizenshipResidencies),
        ];
    }

    protected function getPortfolioIframeCode(?array $portfolioUrl): ?string
    {
        if (null === $portfolioUrl) {
            return null;
        }

        $key = $portfolioUrl['key'] ?? null;
        $type = $portfolioUrl['type'] ?? null;
        $id = $portfolioUrl['id'] ?? '';
        $original = $portfolioUrl['original'] ?? '';

        if ('image' === $type) {
            if (null === $portfolioUrl['original']) {
                return null;
            }

            $string = '<img src="%s" %s />';

            $iframeAttributes = Config::get('candidate-portfolio.iframe.default.attributes');

            $attributes = [
                'height' => $iframeAttributes['height'],
                'width' => $iframeAttributes['width'],
                'alt' => '',
            ];

            $keyedAttributes = [];

            foreach ($attributes as $key => $value) {
                $keyedAttributes[] = "{$key}=\"{$value}\"";
            }

            return sprintf($string, $portfolioUrl['original'], implode(' ', $keyedAttributes));
        }

        [$urlQuery, $attributes] = VideoIdParserHelper::getIframeDataByKey($key);

        return Container::getInstance()->make(VideoIdParser::class)->getIframeCode(
            $key,
            $id,
            $original,
            $urlQuery,
            $attributes,
            $type,
        );
    }

    protected function getCountryFlagsAttribute(
        ?array $country,
        ?array $alternativeCitizenshipResidencies,
    ): ?array {
        $countries = array_merge(
            [$country ?? []],
            $alternativeCitizenshipResidencies ?? [],
        );

        $flags = [];

        $publicPath = Container::getInstance()->publicPath();
        $publicPathLength = mb_strlen($publicPath);
        $flagsFolder = "{$publicPath}/images/common/flags";

        // Don't use "country" name to prevent overriding argument $country.
        foreach ($countries as $item) {
            if (!isset($item['code'])) {
                continue;
            }

            $path = "{$flagsFolder}/{$item['code']}.png";

            if (!file_exists($path)) {
                continue;
            }

            $flags[] = URL::asset(mb_substr($path, $publicPathLength));
        }

        return array_unique($flags);
    }
}
