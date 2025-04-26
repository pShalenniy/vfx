<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLogin(): void
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'admin123',
        ];

        UserCompany::factory()->create();

        User::factory()->create($userData);

        $this
            ->assertDatabaseCount('users', 1)
            ->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $this->post(URL::route('login.post'), $userData)->assertOk();
    }
}
