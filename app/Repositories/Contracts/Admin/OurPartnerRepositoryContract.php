<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Admin;

use Illuminate\Database\Eloquent\Collection;

interface OurPartnerRepositoryContract
{
    public function list(): Collection;
}
