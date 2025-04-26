<?php

declare(strict_types=1);

namespace App\Mail\Client\Subscription;

use App\Mail\Mail;
use App\Mail\Traits\HasSubscriptionDepartments;
use App\Models\EmailTemplateSetting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelledMail extends Mail implements ShouldQueue
{
    use HasSubscriptionDepartments;

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

        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_CANCELLED,
            [
                'user:first_name' => $this->user->getAttribute('first_name'),
                'subscription:end_date' => $subscription->getAttribute('ends_at')->format('d/m/Y'),
            ],
        );
    }
}
