<?php

declare(strict_types=1);

namespace App\Jobs\Elasticsearch;

use App\Jobs\Types\ElasticsearchJob;
use ElasticsearchService;
use Illuminate\Support\Facades\Log;
use Throwable;

class ElasticsearchRetry extends ElasticsearchJob
{
    public int $tries = 2;

    public function __construct(protected string $method, protected array $args)
    {
    }

    /**
     * @throws \Throwable
     */
    public function handle(): void
    {
        try {
            ElasticsearchService::{$this->method}(...$this->args);
        } catch (Throwable $e) {
            Log::notice("[ELASTICSEARCH_RETRY]{$e->getMessage()}");

            throw $e;
        }
    }
}
