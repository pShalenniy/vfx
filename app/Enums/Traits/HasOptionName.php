<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use const null;

trait HasOptionName
{
    public static function getValue(string $key): ?int
    {
        foreach (self::cases() as $commercialExperience) {
            if ($commercialExperience->name === $key) {
                return $commercialExperience->value;
            }
        }

        return null;
    }
}
