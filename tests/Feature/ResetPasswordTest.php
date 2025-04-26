<?php

declare(strict_types=1);

namespace Tests\Feature;

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
            'email' => 'test@example.com',
            'password' => 'test12345',
        ];

        $newPasswordData = [
            'password' => 'testtest1',
            'password_confirmation' => 'testtest1',
        ];

        $token = '62ee217791e9097944361e77d0f6e82d958183ae83f4acdaeca3cda9e0dcdd0b';

        UserCompany::factory()->create();

        $user = User::factory()->create($userData);

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $this
            ->post(URL::route('reset-password.post', [$token, $user->getAttribute('email')]), $newPasswordData)
            ->assertOk();
    }
}
