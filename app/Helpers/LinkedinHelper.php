<?php

declare(strict_types=1);

namespace App\Helpers;

class LinkedinHelper
{
    public static function getRegexValidation(): string
    {
        return '~^(https?://)?(www\.)?([a-z]{2}\.)?linkedin\.com/~';
    }
}
