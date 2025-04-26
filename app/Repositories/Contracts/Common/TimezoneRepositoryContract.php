<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

use Illuminate\Database\Eloquent\Collection;

interface TimezoneRepositoryContract
{
    public function all(): Collection;
}
