<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Client;

use App\Models\Subscription;
use Illuminate\Http\Request;

interface SubscriptionRepositoryContract
{
    public function get(Request $request): ?Subscription;
}
