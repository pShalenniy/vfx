<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

interface UserCompanyRepositoryContract
{
    public function search(string $keyword): array;
}
