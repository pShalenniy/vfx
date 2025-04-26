<?php

declare(strict_types=1);

namespace App\Mail\Client\ForgotPassword;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordMail extends Mail implements ShouldQueue
{
    public function __construct(protected string $resetPasswordUrl)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_FORGOT_PASSWORD,
            ['link' => $this->resetPasswordUrl],
        );
    }
}
