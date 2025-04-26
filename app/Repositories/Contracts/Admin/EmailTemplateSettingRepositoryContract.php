<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Admin;

use Illuminate\Database\Eloquent\Collection;

interface EmailTemplateSettingRepositoryContract
{
    public function list(array $columns = ['*']): Collection;

    public function getEmails(string $key): array;
}
