<?php

declare(strict_types=1);

namespace App\Mail\Client\Subscription;

use App\Mail\Mail;
use App\Mail\Traits\HasSubscriptionDepartments;
use App\Mail\Traits\HasSubscriptionUserInformation;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminRenewPausedMail extends Mail implements ShouldQueue
{
    use HasSubscriptionDepartments;
    use HasSubscriptionUserInformation;

    public function __construct(protected array $users)
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
            EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_PAUSED,
            [
                'loops' => ['users' => $users],
            ],
        );
    }
}
