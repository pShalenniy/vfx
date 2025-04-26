<?php

declare(strict_types=1);

namespace App\Mail\Admin\User;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class CreatedMail extends Mail implements ShouldQueue
{
    public function __construct(protected string $password, protected string $email)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(
            EmailTemplateSetting::KEY_ADMIN_USER_CREATED,
            [
                'login' => $this->email,
                'password' => $this->password,
                'url' => URL::to('/login'),
            ],
        );
    }
}
