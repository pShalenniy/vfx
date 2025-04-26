<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Client;

use Illuminate\Support\Collection;

interface UserRepositoryContract
{
    public function getAdminUsers(): Collection;
}
