<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use App\Jobs\Elasticsearch\ElasticsearchReindexCandidates;
use App\Models\Company;
use Illuminate\Support\Facades\Bus;

class CompanyObserver
{
    public function updated(Company $company): void
    {
        Bus::dispatch(new ElasticsearchReindexCandidates('update', $company));
    }

    public function deleted(Company $company): void
    {
        Bus::dispatch(new SetNullCandidateRelationJob($company->getKey(), 'company_id'));
    }
}
