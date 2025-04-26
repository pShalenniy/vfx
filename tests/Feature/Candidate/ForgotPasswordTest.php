<?php

declare(strict_types=1);

namespace Tests\Feature\Candidate;

use App\Mail\Client\ForgotPassword\ForgotPasswordMail;
use App\Models\Candidate;
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

        Candidate::factory()->create($email);

        $this
            ->assertDatabaseCount('candidates', 1)
            ->assertDatabaseHas('candidates', $email);

        Mail::fake();

        $this->post(URL::route('candidate.forgot-password.post'), $email)->assertStatus(204);

        Mail::assertQueued(ForgotPasswordMail::class);
    }
}
