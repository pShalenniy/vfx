<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use const false;

class SubscriptionController extends Controller
{
    public function renew(Subscription $subscription): void
    {
        DB::transaction(static function () use ($subscription) {
            $subscription
                ->forceFill([
                    'starts_at' => Carbon::now(),
                    'ends_at' => $subscription
                        ->getAttribute('ends_at')
                        ->addMonths($subscription->getAttribute('period')),
                ])
                ->save();
        });
    }

    public function resetHasExpired(Subscription $subscription): void
    {
        DB::transaction(static function () use ($subscription) {
            $subscription->forceFill(['has_expired' => false])->save();
        });
    }
}
