<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\AsSelectOptions;
use App\Enums\Traits\HasOptionName;
use App\Enums\Traits\HasValues;

enum CommercialExperience: int
{
    use AsSelectOptions;
    use HasOptionName;
    use HasValues;

    case YEARS_0_3 = 0;
    case YEARS_3_6 = 3;
    case YEARS_GT6 = 6;

    public static function getMap(int $key): array
    {
        $map = [
            self::YEARS_0_3->value => [
                'lte' => 3,
                'gte' => 1,
            ],
            self::YEARS_3_6->value => [
                'lte' => 6,
                'gte' => 3,
            ],
            self::YEARS_GT6->value => [
                'gte' => 6,
            ],
        ];

        return $map[$key] ?? [];
    }
}
