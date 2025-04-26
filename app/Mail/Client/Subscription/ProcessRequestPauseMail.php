<?php

declare(strict_types=1);

namespace App\Mail\Client\Subscription;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessRequestPauseMail extends Mail implements ShouldQueue
{
    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_PROCESS_REQUEST_PAUSE,
        );
    }
}
