<?php

declare(strict_types=1);

namespace App\Jobs\Types;

use App\Jobs\Job;

abstract class ElasticsearchJob extends Job
{
    public $queue = 'elasticsearch';
}
