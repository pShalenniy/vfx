<?php

declare(strict_types=1);

namespace App\Console\Commands\City;

use App\Console\Helpers\NullProgressBar;
use App\Models\City;
use App\Services\LocationsService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Throwable;

use function array_filter;
use function is_array;

use const null;

class GetCoordinates extends Command
{
    protected $signature = 'city:get-coordinates {--with-progress-bar}';

    protected $description = 'Get cities coordinates';

    public function handle(LocationsService $locationsService): int
    {
        $query = $this->getCitiesQuery();

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar((clone $query)->count())
            : new NullProgressBar();

        $progressBar->start();

        /** @var \App\Models\City $city */
        foreach ($query->lazyById(500, 'cities.id', 'id') as $city) {
            try {
                $coordinates = $this->getCoordinates($locationsService, $city);

                $city->updateQuietly([
                    'latitude' => $coordinates['latitude'] ?? null,
                    'longitude' => $coordinates['longitude'] ?? null,
                ]);
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'country' => $city->getAttribute('country'),
                    'region' => $city->getAttribute('region'),
                    'city' => $city->getAttribute('name'),
                    'id' => $city->getKey(),
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
            ->select([
                'cities.*',
                'regions.name AS region',
                'countries.name AS country',
            ])
            ->join('regions', 'regions.id', '=', 'cities.region_id')
            ->join('countries', 'countries.id', '=', 'regions.country_id')
            ->whereNull(['latitude', 'longitude']);
    }

    protected function getCoordinates(
        LocationsService $locationsService,
        City $city,
    ): ?array {
        $coordinates = $locationsService->getCoordinates(
            $city->getAttribute('country') ?? '',
            $city->getAttribute('region') ?? '',
            $city->getAttribute('name'),
        );

        if (
            empty($coordinates) ||
            (is_array($coordinates) && empty(array_filter($coordinates)))
        ) {
            return null;
        }

        return $coordinates;
    }
}
