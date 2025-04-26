<?php

declare(strict_types=1);

namespace App\Mail\Client\ContactUs;

use App\Mail\Mail;
use App\Models\EmailTemplateSetting;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUsMail extends Mail implements ShouldQueue
{
    public function __construct(protected array $contactData)
    {
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function build(): Mail
    {
        return $this->buildMessage(
            EmailTemplateSetting::KEY_CLIENT_CONTACT_US,
            [
                'user:first_name' => $this->contactData['first_name'],
                'user:last_name' => $this->contactData['last_name'],
                'user:email' => $this->contactData['email'],
                'user:telephone_number' => $this->contactData['telephone_number'],
                'enquiry' => $this->contactData['enquiry'],
            ],
        );
    }
}
