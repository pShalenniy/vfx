<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

class ShortlistFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($shortlist = (int) $request->get('shortlist')) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'term' => [
                    'shortlists' => $shortlist,
                ],
            ];
        }

        return $body;
    }
}
