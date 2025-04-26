<?php

declare(strict_types=1);

namespace Tests\Feature\Candidate;

use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeResetPasswordPage(): void
    {
        $userData = [
            'email' => 'admin@example.com',
            'password' => 'Test234#',
        ];

        $newPasswordData = [
            'password' => 'Test1111#',
            'password_confirmation' => 'Test1111#',
        ];

        $token = '62ee217791e9111144361e77d0f6e82d958183ae83f4acdaeca3cda9e0dcdd0b';

        UserCompany::factory()->create();

        $user = User::factory()->create($userData);

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', ['email' => 'admin@example.com']);

        $this
            ->post(
                URL::route(
                    'candidate.reset-password.post',
                    [$token, $user->getAttribute('email')],
                ),
                $newPasswordData,
            )
            ->assertOk();
    }
}
