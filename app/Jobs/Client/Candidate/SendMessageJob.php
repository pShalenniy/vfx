<?php

declare(strict_types=1);

namespace App\Jobs\Client\Candidate;

use App\Jobs\Types\EmailJob;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMessageJob extends EmailJob
{
    use Dispatchable;
    use InteractsWithQueue;

    public function __construct(
        protected Candidate $candidate,
        protected User $user,
        protected array $messageData,
    ) {
    }

    public function handle(): void
    {
        $subject = $this->messageData['subject'];

        Mail::send(
            'mail.client.candidate.send-message',
            [
                'text' => $this->messageData['message'],
                'url' => "mailto:{$this->user->getAttribute('email')}?subject={$subject}",
            ],
            function (Message $message) use ($subject) {
                $message->to($this->candidate->getAttribute('email'))
                    ->from($this->user->getAttribute('email'))
                    ->subject($subject);
            },
        );
    }
}
