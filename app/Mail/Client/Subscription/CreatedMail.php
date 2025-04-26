<?php

declare(strict_types=1);

namespace App\Mail\Client\Subscription;

use App\Mail\Mail;
use App\Mail\Traits\HasSubscriptionDepartments;
use App\Mail\Traits\HasSubscriptionUserInformation;
use App\Models\EmailTemplateSetting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatedMail extends Mail implements ShouldQueue
{
    use HasSubscriptionDepartments;
    use HasSubscriptionUserInformation;

    public function __construct(protected User $user)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_CREATED,
            $this->getSubscriptionUserInformation($this->user),
        );
    }
}
