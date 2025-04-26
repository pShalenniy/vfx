<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Candidate;

use App\Http\Requests\Admin\Candidate\Traits\HasPreparedAttributesTrait;
use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\JobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Models\Region;
use App\Models\Skill;
use App\Models\TelevisionShow;
use App\Models\Timezone;
use App\Parsers\UrlParsers\VideoIdParsers\VimeoComParser;
use App\Parsers\UrlParsers\VideoIdParsers\YoutubeComParser;
use App\Parsers\UrlParsers\VideoIdParsers\YoutuBeParser;
use App\Validation\Rules\CommercialExperienceRule;
use App\Validation\Rules\CustomMimesRule;
use App\Validation\Rules\IMDBLink;
use App\Validation\Rules\InstagramLink;
use App\Validation\Rules\LinkedinLink;
use App\Validation\Rules\PhoneNumberRule;
use App\Validation\Rules\PortfolioUrlRule;
use App\Validation\Rules\TwitterLink;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;
use McMatters\Helpers\Helpers\ClassHelper;
use McMatters\Helpers\Helpers\ServerHelper;

use function min;

class StoreRequest extends FormRequest
{
    use HasPreparedAttributesTrait;

    public function rules(): array
    {
        $maxFilesize = min(ServerHelper::getUploadMaxFilesize('kb'), 5120);

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', 'unique:candidates,email'],
            'nationalities' => ['nullable', 'array'],
            'nationalities.*.id' => [
                'nullable',
                'integer',
                Rule::exists(Country::class, 'id'),
            ],
            'nationalities.*.name' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'array'],
            'skills.*.id' => [
                'nullable',
                'integer',
                Rule::exists(Skill::class, 'id'),
            ],
            'skills.*.title' => ['nullable', 'string', 'max:255'],
            'skills.*.level' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(CandidateSkill::class, 'LEVEL_')),
            ],
            'want_learn_skills' => ['nullable', 'array'],
            'want_learn_skills.*.id' => [
                'nullable',
                'integer',
                Rule::exists(Skill::class, 'id'),
            ],
            'want_learn_skills.*.title' => ['nullable', 'string', 'max:255'],
            'want_work_with_tools' => ['nullable', 'array'],
            'want_work_with_tools.*.id' => [
                'nullable',
                'integer',
                Rule::exists(Skill::class, 'id'),
            ],
            'want_work_with_tools.*.title' => ['nullable', 'string', 'max:255'],
            'desired_job_roles' => ['nullable', 'array'],
            'desired_job_roles.*.id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'desired_job_roles.*.name' => ['nullable', 'string', 'max:255'],
            'current_job_roles' => ['nullable', 'array'],
            'current_job_roles.*.id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'current_job_roles.*.name' => ['nullable', 'string', 'max:255'],
            'next_promotion_job_roles' => ['nullable', 'array'],
            'next_promotion_job_roles.*.id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'next_promotion_job_roles.*.name' => ['nullable', 'string', 'max:255'],
            'preferred_work_environments' => ['nullable', 'array'],
            'preferred_work_environments.*.id' => [
                'nullable',
                'integer',
                Rule::exists(PreferredWorkEnvironment::class, 'id'),
            ],
            'preferred_work_environments.*.name' => ['nullable', 'string', 'max:255'],
            'picture' => [
                'nullable',
                'file',
                "max:{$maxFilesize}",
                new CustomMimesRule(Config::get('file-extensions.picture')),
            ],
            'imdb_link' => ['nullable', 'string', new IMDBLink()],
            'linkedin_link' => ['nullable', 'string', new LinkedinLink()],
            'instagram_link' => ['nullable', 'string', new InstagramLink()],
            'twitter_link' => ['nullable', 'string', new TwitterLink()],
            'next_availability' => ['required', 'date_format:Y-m-d'],
            'city_id' => [
                'nullable',
                'integer',
                Rule::exists(City::class, 'id'),
            ],
            'region_id' => [
                'nullable',
                'integer',
                Rule::exists(Region::class, 'id'),
            ],
            'country_id' => [
                'nullable',
                'integer',
                Rule::exists(Country::class, 'id'),
            ],
            'timezone_id' => [
                'nullable',
                'integer',
                Rule::exists(Timezone::class, 'id'),
            ],
            'company.id' => [
                'nullable',
                'integer',
                Rule::exists(Company::class, 'id'),
            ],
            'company.name' => ['nullable', 'string', 'max:255'],
            'television_show' => ['nullable', 'array'],
            'television_show.*.id' => [
                'nullable',
                'integer',
                Rule::exists(TelevisionShow::class, 'id'),
            ],
            'television_show.*.name' => ['nullable', 'string', 'max:255'],
            'alternative_citizenship_residencies' => ['nullable', 'array'],
            'alternative_citizenship_residencies.*' => [
                'required',
                'integer',
                Rule::exists(Country::class, 'id'),
            ],
            'budget_of_biggest_show' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Candidate::class, 'BUDGET_OF_BIGGEST_SHOW_')),
            ],
            'phone_number' => ['nullable', 'integer', new PhoneNumberRule()],
            'portfolio_url' => [
                'nullable',
                'string',
                'url',
                new PortfolioUrlRule([VimeoComParser::KEY, YoutubeComParser::KEY, YoutuBeParser::KEY]),
            ],
            'shortfilm_url' => [
                'nullable',
                'string',
                'url',
                new PortfolioUrlRule(),
            ],
            'gross_annual_salary' => ['nullable', 'numeric'],
            'week_rate' => ['nullable', 'numeric'],
            'day_rate' => ['nullable', 'numeric'],
            'would_like_work_on' => ['nullable', 'string'],
            'commercial_experience' => ['nullable', 'integer', new CommercialExperienceRule()],
            'preferred_sectors' => ['nullable', 'array'],
            'preferred_sectors.*.id' => [
                'nullable',
                'integer',
                Rule::exists(PreferredSector::class, 'id'),
            ],
            'preferred_sectors.*.name' => ['nullable', 'string', 'max:255'],
            'preferred_location' => ['nullable', 'array'],
            'preferred_location.*.id' => [
                'required',
                'integer',
                Rule::exists(PreferredLocation::class, 'id'),
            ],
            'preferred_location.*.name' => ['nullable', 'string', 'max:255'],
            'travel_availability' => ['nullable', 'boolean'],
            'salary_rate_currency' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Candidate::class, 'SALARY_RATE_CURRENCY_')),
            ],
            'vfx_notes' => ['nullable', 'string'],
            'skill_circles.x' => ['nullable', 'integer', 'required_with:skill_circles.y'],
            'skill_circles.y' => ['nullable', 'integer', 'required_with:skill_circles.x'],
        ];
    }
}
