<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\Country;
use Illuminate\Support\Facades\Bus;

class CountryObserver
{
    public function updated(Country $country): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $country));
    }

    public function deleted(Country $country): void
    {
        Bus::dispatch(new SetNullCandidateRelationJob($country->getKey(), 'country_id'));
    }
}
