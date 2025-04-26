<?php

declare(strict_types=1);

namespace App\Console\Commands\City;

use App\Console\Helpers\NullProgressBar;
use App\Models\City;
use App\Models\Timezone;
use App\Services\LocationsService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

use function implode;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function trim;
use function usleep;

use const null;

class GetTimezones extends Command
{
    protected $signature = 'city:get-timezones {--with-progress-bar}';

    protected $description = 'Get cities timezones';

    public function handle(LocationsService $locationsService): int
    {
        $query = $this->getCitiesQuery();

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar((clone $query)->count())
            : new NullProgressBar();

        $progressBar->start();

        $timezones = $this->getTimezones();

        /** @var \App\Models\City $city */
        foreach ($query->lazyById() as $city) {
            try {
                $timezoneId = $this->getTimezoneId($locationsService, $city, $timezones);

                if (null === $timezoneId) {
                    continue;
                }

                $city->updateQuietly(['timezone_id' => $timezoneId]);

                // timeapi.io is free, but there is no information about limits, so
                // to prevent any blocks from the service, let sleep 0.5 seconds.
                usleep(500);
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'class' => __CLASS__,
                    'city' => $city->getKey(),
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        return self::SUCCESS;
    }

    protected function getCitiesQuery(): Builder
    {
        return City::query()
            ->whereNotNull(['latitude', 'longitude'])
            ->whereNull('timezone_id');
    }

    protected function getTimezones(): array
    {
        $timezones = [];

        foreach (Timezone::all() as $timezone) {
            $name = trim(
                preg_replace('#\([^)]+\)#', '', $timezone->getAttribute('name')),
            );

            $offset = $this->getTimezoneOffset($timezone);

            $code = $timezone->getAttribute('code');

            if (preg_match('#^[A-Z]+$#', $name)) {
                $timezones[$offset][$code] = $timezone->getKey();

                continue;
            }

            if (preg_match_all('#(?<upper>[A-Z])[a-z]#', $name, $matches)) {
                $upperAbbreviation = implode('', $matches['upper']);

                if ($upperAbbreviation !== $code) {
                    $timezones[$offset][$upperAbbreviation] = $timezone->getKey();
                }
            }

            $timezones[$offset][$code] = $timezone->getKey();
        }

        return $timezones;
    }

    protected function getTimezoneOffset(Timezone $timezone): int
    {
        $offset = $timezone->getAttribute('offset');

        preg_match(
            '#(?<sign>[+-])(?<hour>0[0-9]|1[0-4]):(?<minute>[03]0|[14]5)#',
            $offset,
            $match,
        );

        if (!$match) {
            return 0;
        }

        $seconds = (((int) $match['hour']) * 60 * 60) + (((int) $match['minute']) * 60);

        if ('-' === $match['sign']) {
            $seconds *= -1;
        }

        return $seconds;
    }

    protected function getTimezoneId(
        LocationsService $locationsService,
        City $city,
        array $timezones,
    ): ?int {
        $timezone = $locationsService->getTimezoneByCoordinates(
            $city->getAttribute('latitude'),
            $city->getAttribute('longitude'),
        );

        if (null === $timezone) {
            return null;
        }

        $timezoneCodes = $timezones[$timezone['offset']] ?? null;

        if (empty($timezoneCodes)) {
            return null;
        }

        if (!($timezones['code'] ?? null)) {
            return Arr::first($timezoneCodes);
        }

        return $timezoneCodes[$timezones['code']] ?? null;
    }
}
