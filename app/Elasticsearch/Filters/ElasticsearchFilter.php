<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

interface ElasticsearchFilter
{
    public function apply(Request $request, array $body): array;
}
