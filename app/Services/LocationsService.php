<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use McMatters\Ticl\Client;
use Throwable;

use const null;

class LocationsService
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => Config::get('services.locations_ms.url'),
        ]);
    }

    public function getCoordinates(
        string $country,
        string $region,
        string $city,
    ): ?array {
        try {
            return $this->httpClient
                ->withQuery([
                    'country' => $country,
                    'region' => $region,
                    'city' => $city,
                ])
                ->get('geocoding/coordinates')
                ->json();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [
                'class' => __CLASS__,
                'method' => __FUNCTION__,
                'country' => $country,
                'region' => $region,
                'city' => $city,
            ]);

            return null;
        }
    }

    public function getTimezoneByCoordinates(float $latitude, float $longitude): ?array
    {
        try {
            return $this->httpClient
                ->withQuery(['latitude' => $latitude, 'longitude' => $longitude])
                ->get('geocoding/timezone')
                ->json();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [
                'class' => __CLASS__,
                'method' => __METHOD__,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            return null;
        }
    }
}
