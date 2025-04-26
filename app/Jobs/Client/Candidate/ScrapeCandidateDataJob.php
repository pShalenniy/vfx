<?php

declare(strict_types=1);

namespace App\Jobs\Client\Candidate;

use App\Jobs\Job;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

class ScrapeCandidateDataJob extends Job
{
    use Dispatchable;
    use InteractsWithQueue;

    public int $timeout = 10800;

    public function __construct(
        protected int $candidateId,
        protected string $type,
    ) {
    }

    public function handle(): void
    {
        Artisan::call(
            "candidate:scrape:{$this->type}",
            ['candidate' => $this->candidateId],
        );
    }
}
