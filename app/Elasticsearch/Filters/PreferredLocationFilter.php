<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class PreferredLocationFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($preferredLocation = (int) $request->get('preferred_location_id')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'nested' => [
                    'path' => 'preferred_locations',
                    'query' => [
                        'bool' => [
                            'must' => [
                                'term' => [
                                    'preferred_locations.id' => $preferredLocation,
                                ],
                            ],
                        ],
                    ],
                ],
            ];
        }

        return $body;
    }
}
