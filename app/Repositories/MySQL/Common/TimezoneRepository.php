<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\Timezone;
use App\Repositories\Contracts\Common\TimezoneRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class TimezoneRepository implements TimezoneRepositoryContract
{
    public function all(): Collection
    {
        return Timezone::query()->get();
    }
}
