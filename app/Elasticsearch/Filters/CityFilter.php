<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class CityFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($city = (int) $request->get('city_id')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'term' => [
                    'city.id' => $city,
                ],
            ];
        }

        return $body;
    }
}
