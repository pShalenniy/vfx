<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class CurrentJobRoleFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($currentJobRole = (int) $request->get('current_job_role_id')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'nested' => [
                    'path' => 'current_job_roles',
                    'query' => [
                        'bool' => [
                            'must' => [
                                'term' => [
                                    'current_job_roles.id' => $currentJobRole,
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
