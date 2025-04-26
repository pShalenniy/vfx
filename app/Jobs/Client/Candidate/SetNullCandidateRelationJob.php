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
use const null;

class SetNullCandidateRelationJob extends Job
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    public function __construct(protected int $modelId, protected string $field)
    {
    }

    public function handle(): void
    {
        $relationFieldsMapping = [
            'city_id' => 'city',
            'region_id' => 'region',
            'country_id' => 'country',
            'timezone_id' => 'timezone',
            'company_id' => 'company',
            'preferred_sector_id' => 'preferred_sector',
        ];

        $candidates = Candidate::query()
            ->select(['id', $this->field])
            ->where($this->field, $this->modelId)
            ->lazyById();

        ElasticsearchService::useBulk();

        foreach ($candidates as $candidate) {
            ElasticsearchService::update($candidate, [$relationFieldsMapping[$this->field] => null]);
        }

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);
    }
}
