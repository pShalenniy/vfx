<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Helpers\CandidateHelper;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class CommercialExperienceRule implements Rule
{
    public function passes($attribute, $value): bool
    {
        $maxYear = CandidateHelper::getCommercialExperienceMaxYear();
        $minYear = CandidateHelper::getCommercialExperienceMinYear();

        return $maxYear >= $value && $value >= $minYear;
    }

    public function message(): string
    {
        return Lang::get('validation.commercial_experience', [
            'max' => CandidateHelper::getCommercialExperienceMaxYear(),
            'min' => CandidateHelper::getCommercialExperienceMinYear(),
        ]);
    }
}
