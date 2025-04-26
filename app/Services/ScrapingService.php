<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use McMatters\Ticl\Client;
use Throwable;

use const null;

class ScrapingService
{
    protected Client $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client([
            'base_uri' => Config::get('services.scraping_ms.url'),
        ]);
    }

    public function scrapeFromIMDB(string $url): ?array
    {
        try {
            return $this->httpClient
                ->withQuery(['type' => 'imdb.com', 'url' => $url])
                ->get('/')
                ->json();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [
                'class' => self::class,
                'method' => __FUNCTION__,
            ]);

            return null;
        }
    }

    public function scrapeFromLinkedin(string $url): ?array
    {
        try {
            return $this->httpClient
                ->withQuery([
                    'type' => 'linkedin.com',
                    'url' => $url,
                    'useProxy' => 'true',
                ])
                ->get('/')
                ->json();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [
                'class' => self::class,
                'method' => __FUNCTION__,
            ]);

            return null;
        }
    }
}
