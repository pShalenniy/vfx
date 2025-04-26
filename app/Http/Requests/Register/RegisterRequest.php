<?php

declare(strict_types=1);

namespace App\Http\Requests\Register;

use App\Http\Requests\Traits\HasUserCompanyLogoRules;
use App\Models\City;
use App\Models\Country;
use App\Models\JobRole;
use App\Models\Region;
use App\Models\UserCompany;
use App\Validation\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    use HasUserCompanyLogoRules;

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', 'heimdall'],
            'company' => ['required', 'array'],
            'company.id' => [
                'nullable',
                'integer',
                Rule::exists(UserCompany::class, 'id'),
            ],
            'company.name' => ['required', 'string', 'max:255'],
            'company.url' => ['required', 'string', 'max:500'],
            'company.logo' => $this->getUserCompanyLogoRules(),
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
            'job_title' => ['required', 'string', 'max:255'],
            'preferred_job_roles' => ['nullable', 'array'],
            'preferred_job_roles.*.id' => [
                'nullable',
                'integer',
                Rule::exists(JobRole::class, 'id'),
            ],
            'preferred_job_roles.*.name' => ['nullable', 'string', 'max:255'],
            'has_signatory' => ['required', 'boolean'],
            'phone_number' => ['required', 'string', new PhoneNumberRule()],
            'password' => ['required', 'confirmed', Password::min(6)->numbers()->letters()],
            'password_confirmation' => ['required'],
        ];
    }
}
