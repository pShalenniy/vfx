<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\Timezone;
use Illuminate\Support\Facades\Bus;

class TimezoneObserver
{
    public function updated(Timezone $timezone): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $timezone));
    }

    public function deleted(Timezone $timezone): void
    {
        Bus::dispatch(new SetNullCandidateRelationJob($timezone->getKey(), 'timezone_id'));
    }
}
