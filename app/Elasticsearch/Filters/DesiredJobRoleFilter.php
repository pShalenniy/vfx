<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class DesiredJobRoleFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($desiredJobRole = (int) $request->get('desired_job_role_id')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'nested' => [
                    'path' => 'desired_job_roles',
                    'query' => [
                        'bool' => [
                            'must' => [
                                'term' => [
                                    'desired_job_roles.id' => $desiredJobRole,
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
