<?php

declare(strict_types=1);

namespace App\Console\Helpers;

use const null;

class NullProgressBar
{
    public function setMaxSteps(int $max): void
    {
        //
    }

    public function start(?int $max = null): void
    {
        //
    }

    public function advance(int $step = 1): void
    {
        //
    }

    public function finish(): void
    {
        //
    }
}
