<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Helpers\LinkedinHelper;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

use function preg_match;

class LinkedinLink implements Rule
{
    public function passes($attribute, $value): bool
    {
        return (bool) preg_match(LinkedinHelper::getRegexValidation(), $value);
    }

    public function message(): string
    {
        return Lang::get('validation.link', [
            'link' => 'Linkedin',
        ]);
    }
}
