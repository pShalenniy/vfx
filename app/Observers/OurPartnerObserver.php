<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\OurPartner;
use Illuminate\Support\Facades\Cache;

class OurPartnerObserver
{
    public function saved(): void
    {
        Cache::forget(OurPartner::CACHE_KEY_ALL);
    }

    public function deleted(): void
    {
        Cache::forget(OurPartner::CACHE_KEY_ALL);
    }
}
