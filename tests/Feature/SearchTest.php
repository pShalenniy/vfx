<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Candidate;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function testAnonymousUserNotCanSeeList(): void
    {
        Candidate::factory(20)->create();

        $this->getJson(URL::route('candidate.list'))->assertStatus(401);
    }

    public function testUserCanSeeList(): void
    {
        $this->signInAsUser()
            ->getJson(URL::route('candidate.list'))
            ->assertOk();
    }
}
