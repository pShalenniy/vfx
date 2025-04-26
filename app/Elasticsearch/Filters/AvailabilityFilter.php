<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

use const null;

class AvailabilityFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if ($date = $request->get('next_availability')) {
            try {
                $from = Carbon::parse($date[0]);
                $to = Carbon::parse($date[1]);
            } catch (Throwable) {
                $from = null;
                $to = null;
            } finally {
                if (null !== $from && null !== $to) {
                    $availability = ['gte' => (string) $from];

                    if (!$from->eq($to)) {
                        $availability['lte'] = (string) $to;
                    }

                    $body['query']['bool']['must'][]['constant_score']['filter'] = [
                        'range' => ['next_availability' => $availability],
                    ];
                }
            }
        }

        return $body;
    }
}
