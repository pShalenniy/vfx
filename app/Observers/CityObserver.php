<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\City;
use Illuminate\Support\Facades\Bus;

class CityObserver
{
    public function updated(City $city): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $city));
    }

    public function deleted(City $city): void
    {
        Bus::dispatch(new SetNullCandidateRelationJob($city->getKey(), 'city_id'));
    }
}
