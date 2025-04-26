<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Pivot\CandidateShortlist;
use ElasticsearchService;

class CandidateShortlistObserver
{
    public function created(CandidateShortlist $candidateShortlist): void
    {
        $this->updateShortlistInformation($candidateShortlist);
    }

    public function deleted(CandidateShortlist $candidateShortlist): void
    {
        $this->updateShortlistInformation($candidateShortlist);
    }

    protected function updateShortlistInformation(CandidateShortlist $candidateShortlist): void
    {
        /** @var \App\Models\Candidate $candidate */
        $candidate = $candidateShortlist->getRelationValue('candidate');

        $candidate->load(['shortlists']);

        ElasticsearchService::update($candidate);
    }
}
