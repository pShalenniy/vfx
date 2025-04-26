<?php

declare(strict_types=1);

namespace App\Jobs\Types;

use App\Jobs\Job;

abstract class EmailJob extends Job
{
    public $queue = 'email';
}
