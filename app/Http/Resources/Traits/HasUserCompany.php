<?php

declare(strict_types=1);

namespace App\Http\Resources\Traits;

use App\Models\UserCompany;
use Illuminate\Support\Facades\Storage;

use const null;

trait HasUserCompany
{
    protected function getCompanyAttribute(array $company): array
    {
        if (empty($company)) {
            return [];
        }

        $company['logo'] = $company['logo'] ?? null
            ? Storage::disk(UserCompany::STORAGE_DISK)->url($company['logo'])
            : null;

        return $company;
    }
}
