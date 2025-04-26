<?php

declare(strict_types=1);

namespace App\Mail\Admin\Candidate;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatedMail extends Mail implements ShouldQueue
{
    public function __construct(
        protected string $password,
        protected string $email,
        protected string $loginUrl,
    ) {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(
            EmailTemplateSetting::KEY_ADMIN_CANDIDATE_CREATED,
            [
                'login' => $this->email,
                'password' => $this->password,
                'url' => $this->loginUrl,
            ],
        );
    }
}
