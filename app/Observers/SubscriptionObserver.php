<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use function array_diff;
use function array_merge;

use const null;

class SubscriptionObserver
{
    public function saved(Subscription $subscription, array $payload = []): void
    {
        foreach ($subscription->getHistorableAttributes() as $attribute) {
            if (
                ($payload['relation'] ?? null) instanceof BelongsToMany &&
                $payload['relation']->getRelationName() === $attribute
            ) {
                $newValue = array_diff(
                    array_merge(
                        $payload['changes']['attached'],
                        $payload['changes']['updated'],
                        $payload['previous'],
                    ),
                    $payload['changes']['detached'],
                );

                if ($newValue !== $payload['previous']) {
                    $this->createFieldHistory(
                        $subscription,
                        $attribute,
                        $payload['previous'],
                        $newValue,
                    );
                }
            } elseif (
                $subscription->wasChanged($attribute) &&
                !$subscription->originalIsEquivalent($attribute)
            ) {
                $this->createFieldHistory(
                    $subscription,
                    $attribute,
                    $subscription->getOriginal($attribute),
                    $subscription->getAttribute($attribute),
                );
            }
        }
    }

    protected function createFieldHistory(
        Subscription $subscription,
        string $attribute,
        mixed $previousValue,
        mixed $newValue,
    ): void {
        $subscription->fieldHistories()->create([
            'field' => $attribute,
            'previous_value' => $previousValue,
            'new_value' => $newValue,
            'created_at' => Carbon::now(),
        ]);
    }
}
