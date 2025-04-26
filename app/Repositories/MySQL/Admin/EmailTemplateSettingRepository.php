<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Admin;

use App\Models\EmailTemplateSetting;
use App\Repositories\Contracts\Admin\EmailTemplateSettingRepositoryContract;
use Illuminate\Database\Eloquent\Collection;

class EmailTemplateSettingRepository implements EmailTemplateSettingRepositoryContract
{
    public function list(array $columns = ['*']): Collection
    {
        return EmailTemplateSetting::query()->get($columns);
    }

    public function getEmails(string $key): array
    {
        return EmailTemplateSetting::query()
            ->where('key', $key)
            ->value('emails') ?? [];
    }
}
