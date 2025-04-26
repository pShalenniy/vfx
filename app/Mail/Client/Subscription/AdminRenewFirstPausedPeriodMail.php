<?php

declare(strict_types=1);

namespace App\Mail\Client\Subscription;

use App\Mail\Mail;
use App\Mail\Traits\HasSubscriptionUserInformation;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminRenewFirstPausedPeriodMail extends Mail implements ShouldQueue
{
    use HasSubscriptionUserInformation;

    public function __construct(protected array $users, protected int $daysForRenewal)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        $users = [];

        foreach ($this->users as $user) {
            $users[] = $this->getSubscriptionUserInformation($user);
        }

        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_FIRST_PAUSED_PERIOD,
            [
                'loops' => [
                    'users' => [
                        'data' => $users,
                    ],
                ],
                'subscription:days_for_renewal' => $this->daysForRenewal,
            ],
        );
    }
}
