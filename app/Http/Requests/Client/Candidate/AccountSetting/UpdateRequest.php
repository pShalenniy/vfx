<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Candidate\AccountSetting;

use App\Helpers\PasswordHelper;
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
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use McMatters\Helpers\Helpers\ClassHelper;
use McMatters\Helpers\Helpers\ServerHelper;

use function method_exists;
use function min;
use function ucfirst;

class UpdateRequest extends FormRequest
{
    use HasPreparedAttributesTrait;

    public function rules(): array
    {
        $blockKey = ucfirst(Str::camel($this->get('blockKey')));
        $method = "get{$blockKey}BlockRules";

        return method_exists($this, $method) ? $this->{$method}() : [];
    }

    protected function getContactBlockRules(): array
    {
        return [
            'imdb_link' => ['nullable', 'string', new IMDBLink()],
            'linkedin_link' => ['nullable', 'string', new LinkedinLink()],
            'instagram_link' => ['nullable', 'string', new InstagramLink()],
            'twitter_link' => ['nullable', 'string', new TwitterLink()],
            'phone_number' => ['nullable', 'integer', new PhoneNumberRule()],
        ];
    }

    protected function getGeneralBlockRules(): array
    {
        return [
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
            'commercial_experience' => ['nullable', 'integer', new CommercialExperienceRule()],
            'television_show' => ['nullable', 'array'],
            'television_show.*.id' => [
                'nullable',
                'integer',
                Rule::exists(TelevisionShow::class, 'id'),
            ],
            'company.id' => [
                'nullable',
                'integer',
                Rule::exists(Company::class, 'id'),
            ],
            'company.name' => ['nullable', 'string', 'max:255'],
            'television_show.*.name' => ['nullable', 'string', 'max:255'],
            'budget_of_biggest_show' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Candidate::class, 'BUDGET_OF_BIGGEST_SHOW_')),
            ],
        ];
    }

    protected function getInterestsBlockRules(): array
    {
        return [
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
            'would_like_work_on' => ['nullable', 'string'],
            'next_promotion_job_roles' => ['nullable', 'array'],
            'next_promotion_job_roles.*.id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'next_promotion_job_roles.*.name' => ['nullable', 'string', 'max:255'],
            'desired_job_roles' => ['nullable', 'array'],
            'desired_job_roles.*.id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'desired_job_roles.*.name' => ['nullable', 'string', 'max:255'],
            'preferred_work_environments' => ['nullable', 'array'],
            'preferred_work_environments.*.id' => [
                'nullable',
                'integer',
                Rule::exists(PreferredWorkEnvironment::class, 'id'),
            ],
            'preferred_work_environments.*.name' => ['nullable', 'string', 'max:255'],
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
        ];
    }

    protected function getMainInformationBlockRules(): array
    {
        if ($this->hasFile('picture')) {
            $maxFilesize = min(ServerHelper::getUploadMaxFilesize('kb'), 5120);

            $pictureRules = [
                'nullable',
                'file',
                "max:{$maxFilesize}",
                new CustomMimesRule(Config::get('file-extensions.picture')),
            ];
        } else {
            $pictureRules = ['nullable', 'string'];
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'nationalities' => ['nullable', 'array'],
            'nationalities.*.id' => [
                'nullable',
                'integer',
                Rule::exists(Country::class, 'id'),
            ],
            'nationalities.*.name' => ['nullable', 'string', 'max:255'],
            'current_job_roles' => ['nullable', 'array'],
            'current_job_roles.*.id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'current_job_roles.*.name' => ['nullable', 'string', 'max:255'],
            'picture' => $pictureRules,
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
            'alternative_citizenship_residencies' => ['nullable', 'array'],
            'alternative_citizenship_residencies.*' => [
                'required',
                'integer',
                Rule::exists(Country::class, 'id'),
            ],
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
        ];
    }

    protected function getPasswordBlockRules(): array
    {
        return [
            'password' => [
                PasswordHelper::getPasswordValidationRule()->rules(['confirmed', 'nullable']),
            ],
        ];
    }

    protected function getPersonalBlockRules(): array
    {
        return [
            'gross_annual_salary' => ['nullable', 'numeric'],
            'week_rate' => ['nullable', 'numeric'],
            'day_rate' => ['nullable', 'numeric'],
            'salary_rate_currency' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Candidate::class, 'SALARY_RATE_CURRENCY_')),
            ],
        ];
    }
}
