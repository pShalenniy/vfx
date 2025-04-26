<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Candidate;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class CandidateTest extends TestCase
{
    use RefreshDatabase;

    public function testAnonymousUserCanNotSeeList(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->get(URL::route('candidate.show', $candidate->getAttribute('slug')))
            ->assertStatus(302);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanSeeList(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->signInAsUser()
            ->get(URL::route('candidate.show', $candidate->getAttribute('slug')))
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
        $this->assertDatabaseCount('viewed_candidates', 1);
    }

    public function testUserCantSeeList(): void
    {
        $this->signInAsUser()
            ->get(URL::route('candidate.show', 2))
            ->assertStatus(404);
    }

    protected function candidateData(): array
    {
        return [
            'first_name' => 'Alex',
            'last_name' => 'Kurtzman',
        ];
    }
}
