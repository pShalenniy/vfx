<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function array_merge;
use function range;

class PasswordHelper
{
    public static int $passwordLength = 6;

    public static function getPasswordValidationRule(): Password
    {
        return Password::min(self::$passwordLength)->numbers()->letters();
    }

    public static function generate(): string
    {
        $ranges = array_merge([], range('a', 'z'), range(0, 9));

        do {
            $password = '';

            for ($i = 0; $i < self::$passwordLength; $i++) {
                $password .= Arr::random($ranges);
            }
        } while (!self::validate($password));

        return $password;
    }

    protected static function validate(string $password): bool
    {
        $validator = Validator::make(
            ['password' => $password],
            ['password' => self::getPasswordValidationRule()],
        );

        return !$validator->fails();
    }
}
