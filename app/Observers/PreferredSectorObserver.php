<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\PreferredSector;
use Illuminate\Support\Facades\Bus;

class PreferredSectorObserver
{
    public function updated(PreferredSector $preferredSector): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $preferredSector));
    }

    public function deleted(PreferredSector $preferredSector): void
    {
        Bus::dispatch(
            new SetNullCandidateRelationJob(
                $preferredSector->getKey(),
                'preferred_sector_id',
            ),
        );
    }
}
