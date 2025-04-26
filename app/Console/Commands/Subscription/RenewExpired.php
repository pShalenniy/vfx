<?php

declare(strict_types=1);

namespace App\Console\Commands\Subscription;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use const null;
use const true;

class RenewExpired extends Command
{
    protected $signature = 'subscription:renew:expired {--with-progress-bar}';

    protected $description = 'Renewing expired subscriptions';

    public function handle(): int
    {
        $now = Carbon::now();

        $iterator = Subscription::query()
            ->select(['id', 'period', 'starts_at', 'ends_at'])
            ->where('ends_at', '>=', (string) $now->clone()->subDay()->startOfDay())
            ->where('ends_at', '<=', (string) $now->clone()->subDay()->endOfDay())
            ->lazyById();

        foreach ($iterator as $subscription) {
            try {
                DB::transaction(static function () use ($subscription, $now) {
                    $subscription->forceFill([
                        'starts_at' => $now,
                        'ends_at' => $now->clone()->addMonths($subscription->getAttribute('period')),
                        'has_expired' => true,
                        'reminded_days_ago' => null,
                    ]);

                    $subscription->save();
                });
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $subscription->getKey(),
                ]);
            }
        }

        return self::SUCCESS;
    }
}
