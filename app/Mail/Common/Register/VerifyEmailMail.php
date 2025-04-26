<?php

declare(strict_types=1);

namespace App\Mail\Common\Register;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

use function sha1;

class VerifyEmailMail extends Mail implements ShouldQueue
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        $url = URL::temporarySignedRoute(
            'email.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'user' => $this->user->getKey(),
                'hash' => sha1($this->user->getAttribute('email')),
            ],
        );

        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_VERIFY_EMAIL,
            ['url' => $url],
        );
    }
}
