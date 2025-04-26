<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class JobRoleFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if (!empty($jobRoles = $request->get('job_roles', []))) {
            $keys = [
                'current_job_roles',
                'desired_job_roles',
                'next_promotion_job_roles',
            ];

            $query = [];

            foreach ($keys as $key) {
                $jobRolesQuery = [];

                foreach ($jobRoles as $jobRole) {
                    $jobRolesQuery[] = [
                        'term' => [
                            "{$key}.id" => $jobRole,
                        ],
                    ];
                }

                $query[] = [
                    'nested' => [
                        'path' => $key,
                        'query' => [
                            'bool' => [
                                'should' => $jobRolesQuery,
                            ],
                        ],
                    ],
                ];
            }

            $body['query']['bool']['should'] = $query;
        }

        return $body;
    }
}
