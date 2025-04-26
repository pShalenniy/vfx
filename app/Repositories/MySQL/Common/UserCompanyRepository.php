<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\UserCompany;
use App\Repositories\Contracts\Common\UserCompanyRepositoryContract;
use Illuminate\Support\Facades\Storage;

class UserCompanyRepository implements UserCompanyRepositoryContract
{
    public function search(string $keyword): array
    {
        return UserCompany::query()
            ->select(['id', 'name', 'url', 'logo'])
            ->where('name', 'like', "{$keyword}%")
            ->limit(20)
            ->orderBy('name')
            ->get()
            ->map(static fn (UserCompany $company) => [
                'id' => $company->getKey(),
                'name' => $company->getAttribute('name'),
                'url' => $company->getAttribute('url'),
                'logo' => Storage::disk(UserCompany::STORAGE_DISK)->url(
                    $company->getAttribute('logo'),
                ),
            ])
            ->all();
    }
}
