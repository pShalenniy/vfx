<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Timezone;

class TimezoneHelper
{
    public static function getTimezonesList(): array
    {
        $timezonesList = [];
        $timezones = Timezone::query()
            ->select(['id', 'name', 'offset'])
            ->get();

        foreach ($timezones as $timezone) {
            $timezoneName = $timezone->getAttribute('name');
            $timezoneOffset = $timezone->getAttribute('offset');

            $timezonesList[] = [
                'id' => $timezone->getKey(),
                'name' => "{$timezoneName} - {$timezoneOffset}",
            ];
        }

        return $timezonesList;
    }
}
