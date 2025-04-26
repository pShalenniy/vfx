<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\TelevisionShow;
use Illuminate\Support\Facades\Bus;

class TelevisionShowObserver
{
    public function updated(TelevisionShow $televisionShow): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $televisionShow));
    }

    public function deleted(TelevisionShow $televisionShow): void
    {
        Bus::dispatch(new SetNullCandidateRelationJob($televisionShow->getKey(), 'television_show_id'));
    }
}
