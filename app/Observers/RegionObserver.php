<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\Region;
use Illuminate\Support\Facades\Bus;

class RegionObserver
{
    public function updated(Region $region): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $region));
    }

    public function deleted(Region $region): void
    {
        Bus::dispatch(new SetNullCandidateRelationJob($region->getKey(), 'region_id'));
    }
}
