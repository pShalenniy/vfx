<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use App\Helpers\SubscriptionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use const null;

class DepartmentFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        $department = $request->get('department');

        if (null === $department) {
            $departments = SubscriptionHelper::availableDepartments($request->user());

            $bodyContent = [
                'terms' => [
                    'departments' => Arr::pluck($departments, 'id'),
                ],
            ];
        } else {
            $bodyContent = [
                'term' => [
                    'departments' => (int) $department,
                ],
            ];
        }

        $body['query']['bool']['must'][]['constant_score']['filter'] = $bodyContent;

        return $body;
    }
}
