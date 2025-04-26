<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class ContactUsTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCantSendContactUsRequest(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'telephone_number' => '38 000 000 000',
            'enquiry' => 'Test test',
        ];

        $this->post(URL::route('contact-us.post'), $userData)->assertStatus(302);
    }

    public function testUserCanSendContactUsRequest(): void
    {
        $userData = [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'test@example.com',
            'telephone_number' => '38 000 000 000',
            'enquiry' => 'Test test',
        ];

        $this->post(URL::route('contact-us.post'), $userData)->assertStatus(204);
    }
}
