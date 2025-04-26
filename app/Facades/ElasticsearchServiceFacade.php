<?php

declare(strict_types=1);

namespace App\Facades;

use App\Jobs\Elasticsearch\ElasticsearchRetry;
use App\Services\ElasticsearchService;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Facade;

use function in_array;
use function usleep;

use const null;
use const true;

class ElasticsearchServiceFacade extends Facade
{
    protected static int $maxRetries = 3;

    protected static array $retryMethods = [
        'index',
        'update',
        'delete',
    ];

    /**
     * @param mixed $method
     * @param mixed $args
     *
     * @throws \RuntimeException
     * @throws \Exception
     */
    public static function __callStatic($method, $args)
    {
        if (!Config::get('elasticsearch.settings.enabled')) {
            return [];
        }

        $e = null;

        for ($i = 0; $i < self::$maxRetries; $i++) {
            try {
                $result = parent::__callStatic($method, $args);

                break;
            } catch (NoNodesAvailableException $e) {
                usleep(1000);

                /** @var \App\Services\ElasticsearchService|null $instance */
                if (null !== ($instance = static::getFacadeRoot())) {
                    $instance->setClient();
                }
            }
        }

        if (
            $e instanceof NoNodesAvailableException &&
            in_array($method, self::$retryMethods, true)
        ) {
            Bus::dispatch(new ElasticsearchRetry($method, $args));
        }

        if (null !== $e) {
            throw $e;
        }

        return $result ?? null;
    }

    protected static function getFacadeAccessor(): string
    {
        return ElasticsearchService::class;
    }
}
