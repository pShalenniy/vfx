<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use App\Enums\CommercialExperience;
use Illuminate\Http\Request;

use const null;

class CommercialExperienceFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if (null !== ($commercialExperience = $request->get('commercial_experience'))) {
            $body['query']['bool']['must'][]['constant_score']['filter'] = [
                'range' => [
                    'commercial_experience' => CommercialExperience::getMap((int) $commercialExperience),
                ],
            ];
        }

        return $body;
    }
}
