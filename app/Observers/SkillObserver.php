<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\DetachCandidateSkillJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\Skill;
use Illuminate\Support\Facades\Bus;

class SkillObserver
{
    public function updated(Skill $skill): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $skill));
    }

    public function deleted(Skill $skill): void
    {
        Bus::dispatch(new DetachCandidateSkillJob($skill->getKey()));
    }
}
