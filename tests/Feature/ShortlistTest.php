<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Department;
use App\Models\Shortlist;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class ShortlistTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeList(): void
    {
        $this->signInAsUser()
            ->getJson(URL::route('shortlist.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                    ],
                ],
            ]);
    }

    public function testUserCanStoreShortlist(): void
    {
        $this->signInAsUser()
            ->post(URL::route('shortlist.store'), $this->shortlistData())
            ->assertStatus(201);

        $this->assertDatabaseCount('shortlists', 1);
    }

    public function testUserCanSyncCandidate(): void
    {
        UserCompany::factory()->create();

        $user = User::factory()->create();
        $shortlist = Shortlist::factory()->create($this->shortlistData());
        $candidate = Candidate::factory()->create();

        $department = Department::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->getKey()]);

        $subscription->departments()->sync($department->value('id'));

        $this->actingAs($user)
            ->patch(
                URL::route('shortlist.candidate.sync', $shortlist->getKey()),
                ['candidates' => [$candidate->getKey()]],
            )
            ->assertStatus(201);

        $this->assertDatabaseCount('shortlists', 1)->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanDestroyCandidate(): void
    {
        UserCompany::factory()->create();

        $user = User::factory()->create();
        $shortlist = Shortlist::factory()->create($this->shortlistData());
        $candidate = Candidate::factory()->create();

        $shortlist->candidates()->sync($candidate->getKey());

        $department = Department::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->getKey()]);

        $subscription->departments()->sync($department->value('id'));

        $this->assertDatabaseCount('candidate_shortlist', 1);

        $this->actingAs($user)
            ->post(URL::route('shortlist.candidate.detach', [
                'shortlist' => $shortlist->getKey(),
                'candidate' => $candidate->getKey(),
            ]))
            ->assertStatus(204);

        $this->assertDatabaseCount('shortlists', 1)
            ->assertDatabaseCount('candidates', 1)
            ->assertDatabaseCount('candidate_shortlist', 0);
    }

    public function testUserCanDestroyShortlist(): void
    {
        UserCompany::factory()->create();

        $user = User::factory()->create();
        $shortlist = Shortlist::factory()->create([
            'title' => 'test',
            'user_id' => $user->getKey(),
        ]);

        $department = Department::factory()->create();
        $subscription = Subscription::factory()->create(['user_id' => $user->getKey()]);

        $subscription->departments()->sync($department->value('id'));

        $this->actingAs($user)
            ->delete(URL::route('shortlist.destroy', $shortlist->getKey()))
            ->assertStatus(204);
    }

    protected function shortlistData(): array
    {
        return ['title' => 'test'];
    }
}
