<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use function array_map;

trait HasValues
{
    public static function values(): array
    {
        return array_map(static fn ($case) => $case->value, self::cases());
    }
}
