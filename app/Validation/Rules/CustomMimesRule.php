<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

use function implode;
use function in_array;

use const true;

class CustomMimesRule implements Rule
{
    public function __construct(protected array $extensions)
    {
    }

    public function passes($attribute, $value): bool
    {
        return in_array($value->getClientOriginalExtension(), $this->extensions, true);
    }

    public function message(): string
    {
        return Lang::get('validation.custom_mimes', [
            'extensions' => implode(', ', $this->extensions),
        ]);
    }
}
