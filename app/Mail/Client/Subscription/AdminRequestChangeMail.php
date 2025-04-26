<?php

declare(strict_types=1);

namespace App\Mail\Client\Subscription;

use App\Mail\Mail;
use App\Mail\Traits\HasSubscriptionDepartments;
use App\Models\EmailTemplateSetting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminRequestChangeMail extends Mail implements ShouldQueue
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
        $subscription = $this->user->getRelationValue('subscription') ?? new Subscription();

        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_REQUEST_CHANGE,
            [
                'user:first_name' => $this->user->getAttribute('first_name'),
                'user:last_name' => $this->user->getAttribute('last_name'),
                'user:email' => $this->user->getAttribute('email'),
                'user:phone_number' => $this->user->getAttribute('phone_number'),
                'subscription:seats' => $subscription->getAttribute('seats'),
                'subscription:departments' => $this->getDepartments($subscription),
            ],
        );
    }
}
