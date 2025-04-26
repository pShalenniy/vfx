<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Skill;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class SkillTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeList(): void
    {
        Skill::factory()->create();

        $this->signInAsAdmin()
            ->getJson(URL::route('admin.skill.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'created_at',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('skills', 1);
    }

    public function testUserCanNotStore(): void
    {
        $this->signInAsUser()
            ->post(URL::route('admin.skill.store'), $this->skillData())
            ->assertStatus(403);

        $this->assertDatabaseCount('skills', 0);
    }

    public function testUserCanStore(): void
    {
        $this->signInAsAdmin()
            ->post(URL::route('admin.skill.store'), $this->skillData())
            ->assertStatus(201);

        $this->assertDatabaseCount('skills', 1);
    }

    public function testUserCanNotUpdate(): void
    {
        $skill = Skill::factory()->create();

        $this->signInAsUser()
            ->patch(URL::route('admin.skill.update', $skill), $this->skillEditData())
            ->assertStatus(403);

        $this->assertDatabaseCount('skills', 1);
    }

    public function testUserCanUpdate(): void
    {
        $skill = Skill::factory()->create();

        $this->signInAsAdmin()
            ->patch(URL::route('admin.skill.update', $skill), $this->skillEditData())
            ->assertStatus(201);

        $this->assertDatabaseCount('skills', 1);
    }

    public function testUserCanNotDelete(): void
    {
        $skill = Skill::factory()->create($this->skillData());

        $this->signInAsUser()
            ->delete(URL::route('admin.skill.destroy', $skill), $this->skillData())
            ->assertStatus(403);
    }

    public function testUserCanDelete(): void
    {
        $skill = Skill::factory()->create($this->skillData());

        $this->signInAsAdmin()
            ->delete(URL::route('admin.skill.destroy', $skill), $this->skillData())
            ->assertStatus(204);

        $this->assertDatabaseMissing('skills', $this->skillData());
    }

    protected function skillData(): array
    {
        return [
            'title' => 'Bar',
        ];
    }

    protected function skillEditData(): array
    {
        return [
            'title' => 'Foo',
        ];
    }
}
