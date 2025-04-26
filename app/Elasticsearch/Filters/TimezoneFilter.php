<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class TimezoneFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($timezone = (int) $request->get('timezone_id')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'term' => [
                    'timezone.id' => $timezone,
                ],
            ];
        }

        return $body;
    }
}
