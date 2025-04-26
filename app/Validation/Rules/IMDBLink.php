<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Helpers\IMDBHelper;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

use function preg_match;

class IMDBLink implements Rule
{
    public function passes($attribute, $value): bool
    {
        return (bool) preg_match(IMDBHelper::getRegexValidation(), $value);
    }

    public function message(): string
    {
        return Lang::get('validation.link', [
            'link' => 'IMDB',
        ]);
    }
}
