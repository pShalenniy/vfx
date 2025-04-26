<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\Candidate;

use App\Enums\CommercialExperience;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\Shortlist;
use App\Models\Skill;
use App\Models\TelevisionShow;
use App\Models\Timezone;
use App\Validation\Rules\UserSubscribedDepartmentRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use McMatters\Helpers\Helpers\ClassHelper;

class ListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'city_id' => [
                'nullable',
                'integer',
                Rule::exists(City::class, 'id'),
            ],
            'commercial_experience' => [
                'nullable',
                'integer',
                Rule::in(CommercialExperience::values()),
            ],
            'company_id' => [
                'nullable',
                'integer',
                Rule::exists(Company::class, 'id'),
            ],
            'country_id' => [
                'nullable',
                'integer',
                Rule::exists(Country::class, 'id'),
            ],
            'current_role_id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'desired_job_role_id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'keyword' => ['nullable', 'string', 'max:255'],
            'next_availability' => ['nullable', 'array', 'max:2'],
            'next_availability.*' => ['nullable', 'date_format:Y-m-d'],
            'department' => [
                'nullable',
                'integer',
                Rule::exists(Department::class, 'id'),
                new UserSubscribedDepartmentRule($this->user()),
            ],
            'preferred_location_id' => [
                'nullable',
                'integer',
                Rule::exists(PreferredLocation::class, 'id'),
            ],
            'shortlist' => [
                'nullable',
                'integer',
                Rule::exists(Shortlist::class, 'id'),
            ],
            'skills' => ['nullable', 'array'],
            'skills.*.id' => [
                'nullable',
                'integer',
                Rule::exists(Skill::class, 'id'),
            ],
            'skills.*.level' => [
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(CandidateSkill::class, 'LEVEL_')),
            ],
            'television_show' => ['nullable', 'array'],
            'television_show.*.imdb_id' => [
                'nullable',
                'string',
                Rule::exists(TelevisionShow::class, 'imdb_id'),
            ],
            'timezone_id' => [
                'nullable',
                'integer',
                Rule::exists(Timezone::class, 'id'),
            ],
        ];
    }
}
