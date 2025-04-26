<?php

declare(strict_types=1);

namespace App\Http\Requests\Client\User;

use App\Helpers\PasswordHelper;
use App\Http\Requests\Traits\HasUserCompanyLogoRules;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\UserCompany;
use App\Validation\Rules\PhoneNumberRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use function method_exists;
use function ucfirst;

class UpdateRequest extends FormRequest
{
    use HasUserCompanyLogoRules;

    public function rules(): array
    {
        $blockKey = ucfirst(Str::camel($this->get('blockKey')));
        $method = "get{$blockKey}BlockRules";

        return method_exists($this, $method) ? $this->{$method}() : [];
    }

    protected function getCompanyBlockRules(): array
    {
        return [
            'company' => ['required', 'array'],
            'company.id' => [
                'nullable',
                'integer',
                Rule::exists(UserCompany::class, 'id'),
            ],
            'company.name' => ['required', 'string', 'max:255'],
            'company.url' => ['required', 'string', 'max:500'],
            'company.logo' => $this->getUserCompanyLogoRules(),
        ];
    }

    protected function getJobTitleBlockRules(): array
    {
        return [
            'job_title' => ['required', 'string', 'max:255'],
        ];
    }

    protected function getLocationBlockRules(): array
    {
        return [
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
        ];
    }

    protected function getPhoneNumberBlockRules(): array
    {
        return [
            'phone_number' => ['required', 'string', new PhoneNumberRule()],
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
}
