<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class ExcludeIdFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        $query = [];

        if (!empty($exclude = (array) $request->get('exclude_id'))) {
            foreach ($exclude as $id) {
                $query[] = [
                    'term' => [
                        'id' => (int) $id,
                    ],
                ];
            }

            $body['query']['bool']['must'][]['constant_score']['filter']['bool']['must_not'] = $query;
        }

        return $body;
    }
}
