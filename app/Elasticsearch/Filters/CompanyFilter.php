<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class CompanyFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($company = (int) $request->get('company_id')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'term' => [
                    'company.id' => $company,
                ],
            ];
        }

        return $body;
    }
}
