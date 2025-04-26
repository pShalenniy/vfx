<?php

declare(strict_types=1);

namespace App\Elasticsearch\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

abstract class ElasticsearchResource implements Arrayable
{
    public function __construct(protected Model $resource)
    {
    }

    abstract public function toArray(): array;
}
