<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class TelevisionShowFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if (!empty($televisionShows = $request->get('television_shows', []))) {
            $query = [];

            foreach ($televisionShows as $IMDBId) {
                $keys = [
                    'filmographies',
                    'television_shows',
                ];

                foreach ($keys as $key) {
                    $query[] = [
                        'bool' => [
                            'must' => [
                                'nested' => [
                                    'path' => $key,
                                    'query' => [
                                        'term' => [
                                            "{$key}.imdb_id" => $IMDBId,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ];
                }
            }

            $body['query']['bool']['must'][]['constant_score']['filter']['bool']['should'] = $query;
        }

        return $body;
    }
}
