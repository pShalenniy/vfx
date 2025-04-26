<?php

declare(strict_types=1);

namespace Tests\Feature\Candidate;

use App\Models\Candidate;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testCandidateCanLogin(): void
    {
        $candidateData = [
            'email' => 'test@example.com',
            'password' => 'admin123',
        ];

        Candidate::factory()->create($candidateData);

        $this
            ->assertDatabaseCount('candidates', 1)
            ->assertDatabaseHas('candidates', ['email' => 'test@example.com']);

        $this->post(URL::route('candidate.login.post'), $candidateData)->assertOk();
    }

    public function testCandidateCanNotLogin(): void
    {
        $candidateData = [
            'email' => 'test@example.com',
            'password' => 'admin123',
        ];

        $this->postJson(URL::route('candidate.login.post'), $candidateData)->assertStatus(422);
    }
}
