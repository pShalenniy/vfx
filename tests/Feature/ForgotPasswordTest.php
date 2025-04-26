<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\Client\ForgotPassword\ForgotPasswordMail;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testSendLink(): void
    {
        $email = ['email' => 'test@example.com'];

        UserCompany::factory()->create();

        User::factory()->create($email);

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', $email);

        Mail::fake();

        $this->post(URL::route('forgot-password.post'), $email)->assertStatus(204);

        Mail::assertQueued(ForgotPasswordMail::class);
    }
}
