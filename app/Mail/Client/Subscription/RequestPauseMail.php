<?php

declare(strict_types=1);

namespace App\Mail\Client\Subscription;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestPauseMail extends Mail implements ShouldQueue
{
    public function __construct(protected User $user)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        $subscription = $this->user->getRelationValue('subscription');

        $subscriptionHistory = $subscription->fieldHistories()
            ->select(['created_at'])
            ->where('field', 'status')
            ->where('previous_value', Subscription::STATUS_ACTIVE)
            ->where('new_value', Subscription::STATUS_PAUSED)
            ->orderByDesc('created_at')
            ->first();

        $pauseSubscriptionDate = $subscriptionHistory?->getAttribute('created_at');

        $pauseSubscriptionEndDate = $pauseSubscriptionDate
            ? $pauseSubscriptionDate
                ->addMonths(Subscription::PAUSE_MONTH_PERIOD)
                ->format('d/m/Y')
            : '-';

        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_REQUEST_PAUSE,
            [
                'user:first_name' => $this->user->getAttribute('first_name'),
                'subscription:end_date' => $pauseSubscriptionDate?->format('d/m/Y') ?? '-',
                'subscription:period' => $subscription->getAttribute('period'),
                'subscription:pause_end_date' => $pauseSubscriptionEndDate,
            ],
        );
    }
}
