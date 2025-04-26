<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ActiveSession;
use App\Models\Department;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class ActiveSessionTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeActiveSessionsListWithOneActiveSession(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'admin123',
        ];

        UserCompany::factory()->create();

        /** @var \App\Models\Contracts\HasActiveSession $user */
        $user = User::factory()->create($userData);

        $this->createSubscription($user->getKey());

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $response = $this->post(URL::route('login.post'), $userData);

        $response->assertOk();

        $body = $response->json();

        $this->withToken($body['token'])
            ->get(URL::route('candidate.list'))
            ->assertOk();

        $this->withToken($body['token'])
            ->get(URL::route('active-session.view'))
            ->assertStatus(302);

        $this
            ->assertDatabaseCount('personal_access_tokens', 1)
            ->assertDatabaseCount('active_sessions', 1);
    }

    public function testUserCanNotSeeActiveSessionsList(): void
    {
        $this->get(URL::route('active-session.view'))->assertStatus(302);
    }

    public function testUserCanDeleteActiveSession(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'admin123',
        ];

        UserCompany::factory()->create();

        /** @var \App\Models\Contracts\HasActiveSession $user */
        $user = User::factory()->create($userData);

        $this->createSubscription($user->getKey());

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $response = $this->post(URL::route('login.post'), $userData);

        $response->assertOk();

        $body = $response->json();

        $this->withToken($body['token'])
            ->get(URL::route('candidate.list'))
            ->assertOk();

        $this
            ->assertDatabaseCount('personal_access_tokens', 1)
            ->assertDatabaseCount('active_sessions', 1);

        $tokenId = PersonalAccessToken::findToken($body['token'])->getKey();

        $activeSession = ActiveSession::query()
            ->where('token_id', $tokenId)
            ->first();

        $this->actingAs($user)
            ->delete(URL::route('active-session.destroy', $activeSession->getKey()))
            ->assertNoContent();

        $this
            ->assertDatabaseCount('personal_access_tokens', 0)
            ->assertDatabaseCount('active_sessions', 0);
    }

    public function testUserCanNotDeleteActiveSession(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'admin123',
        ];

        UserCompany::factory()->create();

        /** @var \App\Models\Contracts\HasActiveSession $user */
        $user = User::factory()->create($userData);

        $this->createSubscription($user->getKey());

        $this->actingAs($user)
            ->deleteJson(URL::route('active-session.destroy', 100))
            ->assertStatus(404);
    }

    protected function createSubscription(int $userId): void
    {
        $department = Department::factory()->create();

        $subscription = Subscription::factory()->create(['user_id' => $userId]);

        $subscription->departments()->sync($department->getKey());
    }
}
