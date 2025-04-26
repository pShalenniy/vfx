<?php

declare(strict_types=1);

namespace App\Mail\Client\ResetPassword;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class ResetPasswordMail extends Mail implements ShouldQueue
{
    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_RESET_PASSWORD,
            [
                'link' => URL::route('login.view'),
            ],
        );
    }
}
