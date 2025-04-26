<?php

declare(strict_types=1);

namespace App\Mail\Admin\User;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletedMail extends Mail implements ShouldQueue
{
    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(EmailTemplateSetting::KEY_ADMIN_USER_DELETED);
    }
}
