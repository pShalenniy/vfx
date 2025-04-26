<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Admin;

use App\Models\OurPartner;
use App\Repositories\Contracts\Admin\OurPartnerRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class OurPartnerRepository implements OurPartnerRepositoryContract
{
    public function list(): Collection
    {
        return Cache::rememberForever(
            OurPartner::CACHE_KEY_ALL,
            static fn () => OurPartner::query()->get(['id', 'logo', 'created_at']),
        );
    }
}
