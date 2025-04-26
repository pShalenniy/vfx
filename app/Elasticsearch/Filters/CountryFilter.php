<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class CountryFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($country = (int) $request->get('country_id')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'term' => [
                    'country.id' => $country,
                ],
            ];
        }

        return $body;
    }
}
