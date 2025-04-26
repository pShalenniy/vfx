<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Timezone;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class TimezoneTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeList(): void
    {
        $this->signInAsUser()
            ->getJson(URL::route('common.timezone.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'code',
                        'title',
                        'offset',
                    ],
                ],
            ]);
    }

    public function testUserCanNotStore(): void
    {
        $this->signInAsUser()
            ->post(URL::route('admin.timezone.store'), $this->timezoneData())
            ->assertStatus(403);

        $this->assertDatabaseCount('timezones', 0);
    }

    public function testUserCanNotStoreWithInvalidOffsetStore(): void
    {
        $timezoneData = [
            'code' => 'AST',
            'title' => 'Atlantic Standard Time',
            'offset' => 4,
        ];

        $this->signInAsAdmin()
            ->postJson(URL::route('admin.timezone.store'), $timezoneData)
            ->assertStatus(422);
    }

    public function testUserCanStore(): void
    {
        $this->signInAsAdmin()
            ->post(URL::route('admin.timezone.store'), $this->timezoneData())
            ->assertStatus(201);
    }

    public function testUserCanNotUpdate(): void
    {
        $timezone = Timezone::factory()->create();

        $this->signInAsUser()
            ->patch(URL::route('admin.timezone.update', $timezone), $this->timezoneEditData())
            ->assertStatus(403);
    }

    public function testUserCanUpdate(): void
    {
        $timezone = Timezone::factory()->create();

        $this->signInAsAdmin()
            ->patch(URL::route('admin.timezone.update', $timezone), $this->timezoneEditData())
            ->assertStatus(200);
    }

    public function testAdminCanDelete(): void
    {
        $timezone = Timezone::factory()->create();

        $this->signInAsAdmin()
            ->delete(URL::route('admin.timezone.destroy', $timezone))
            ->assertOk();
    }

    public function testUserCanNotDelete(): void
    {
        $timezone = Timezone::factory()->create();

        $this->signInAsUser()
            ->delete(URL::route('admin.timezone.destroy', $timezone))
            ->assertStatus(403);
    }

    protected function timezoneData(): array
    {
        return [
            'code' => 'AST',
            'name' => 'Atlantic Standard Time',
            'offset' => 'UTC-01:00',
        ];
    }

    protected function timezoneEditData(): array
    {
        return [
            'code' => 'NST',
            'name' => 'Newfoundland Standard Time',
            'offset' => 'UTC-02:00',
        ];
    }
}
