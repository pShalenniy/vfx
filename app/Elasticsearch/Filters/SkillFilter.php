<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class SkillFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if (!empty($skills = $request->get('skills', []))) {
            $skillQuery = [];

            foreach ($skills as $skill) {
                $skillQuery[] = [
                    'bool' => [
                        'must' => [
                            [
                                'term' => [
                                    'skills.id' => $skill['id'],
                                ],
                            ],
                            [
                                'term' => [
                                    'skills.level' => $skill['level'],
                                ],
                            ],
                        ],
                    ],
                ];
            }

            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'nested' => [
                    'path' => 'skills',
                    'query' => $skillQuery,
                ],
            ];
        }

        return $body;
    }
}
