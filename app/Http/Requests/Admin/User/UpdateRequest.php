<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\User;

use App\Helpers\PasswordHelper;
use App\Http\Requests\Traits\HasUserCompanyLogoRules;
use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Region;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\UserCompany;
use App\Validation\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use McMatters\Helpers\Helpers\ClassHelper;

class UpdateRequest extends FormRequest
{
    use HasUserCompanyLogoRules;

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignoreModel($this->route('user')),
            ],
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
            'phone_number' => ['nullable', 'string', new PhoneNumberRule()],
            'password' => [PasswordHelper::getPasswordValidationRule()->rules(['confirmed', 'nullable'])],
            'role_id' => [
                'nullable',
                Rule::exists(Role::class, 'id'),
            ],
            'subscription' => ['nullable', 'array'],
            'subscription.status' => [
                Rule::requiredIf(fn () => $this->hasAny([
                    'subscription.period',
                    'subscription.seats',
                    'subscription.departments',
                ])),
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Subscription::class, 'STATUS_')),
            ],
            'subscription.period' => [
                Rule::requiredIf(fn () => $this->hasAny([
                    'subscription.status',
                    'subscription.seats',
                    'subscription.departments',
                ])),
                'nullable',
                'integer',
                Rule::in(ClassHelper::getConstantsStartWith(Subscription::class, 'PERIOD_')),
            ],
            'subscription.seats' => [
                Rule::requiredIf(fn () => $this->hasAny([
                    'subscription.status',
                    'subscription.period',
                    'subscription.departments',
                ])),
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],
            'subscription.departments' => [
                Rule::requiredIf(fn () => $this->hasAny([
                    'subscription.status',
                    'subscription.period',
                    'subscription.seats',
                ])),
                'nullable',
                'array',
            ],
            'subscription.departments.*' => [
                'required',
                'integer',
                Rule::exists(Department::class, 'id'),
            ],
            'subscription.contract_signed' => ['nullable', 'boolean'],
        ];
    }
}
