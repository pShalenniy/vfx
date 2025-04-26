<?php

declare(strict_types=1);

namespace App\Jobs\Client\Candidate;

use App\Jobs\Job;
use App\Models\Candidate;
use ElasticsearchService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

use const false;

class DetachCandidateSkillJob extends Job
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(protected int $modelId)
    {
    }

    public function handle(): void
    {
        $candidates = Candidate::query()
            ->select(['id'])
            ->join('candidate_skill', 'candidates.id', '=', 'candidate_skill.candidate_id')
            ->where('candidate_skill.skill_id', $this->modelId)
            ->lazyById();

        ElasticsearchService::useBulk();

        foreach ($candidates as $candidate) {
            $candidate->skills()->detach($this->modelId);

            ElasticsearchService::update($candidate);
        }

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);
    }
}
