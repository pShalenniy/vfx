<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use AMgrade\SingleRole\Models\Role;
use App\Models\Permission;
use Database\Seeders\TestRoleSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeList(): void
    {
        $this->signInAsAdmin()
            ->getJson(URL::route('admin.role.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'permissions',
                    ],
                ],
            ]);
    }

    public function testUserCanNotStore(): void
    {
        Artisan::call('role:setup');

        $this->seed(TestRoleSeeder::class);

        $permission = Permission::query()->first();

        $this->signInAsUser()
            ->post(URL::route('admin.role.store'), [
                'name' => 'Editor',
                'permissions' => [$permission->getKey()],
            ])
            ->assertStatus(403);
    }

    public function testUserCanNotStoreWithInvalidPermission(): void
    {
        $this->signInAsUser()
            ->post(URL::route('admin.role.store'), [
                'name' => 'Foo',
                'permissions' => 'Bar',
            ])
            ->assertStatus(403);
    }

    public function testUserCanStore(): void
    {
        Artisan::call('role:setup');

        $this->seed(TestRoleSeeder::class);

        $permission = Permission::query()->first();

        $this->signInAsAdmin()
            ->post(URL::route('admin.role.store'), [
                'name' => 'Manager',
                'permissions' => [$permission->getKey()],
            ])
            ->assertStatus(201);
    }

    public function testUserCanNotUpdate(): void
    {
        Artisan::call('role:setup');

        $this->seed(TestRoleSeeder::class);

        $role = Role::query()->first();

        $permission = Permission::query()->first();

        $this->signInAsUser()
            ->patch(URL::route('admin.role.update', $role), [
                'name' => 'User',
                'permissions' => [$permission->getKey()],
            ])
            ->assertStatus(403);
    }

    public function testUserCanUpdate(): void
    {
        Artisan::call('role:setup');

        $this->seed(TestRoleSeeder::class);

        $role = Role::query()->first();

        $permission = Permission::query()->first();

        $this->signInAsAdmin()
            ->patch(URL::route('admin.role.update', $role), [
                'name' => 'Administrator',
                'permissions' => [$permission->getKey()],
            ])
            ->assertStatus(201);
    }

    public function testRoleCanNotDelete(): void
    {
        $this->seed(TestRoleSeeder::class);

        $role = Role::query()->first();

        $this->signInAsUser()
            ->delete(URL::route('admin.role.destroy', $role))
            ->assertStatus(403);
    }

    public function testRoleCanDelete(): void
    {
        $this->seed(TestRoleSeeder::class);

        $role = Role::query()->first();

        $this->signInAsAdmin()
            ->delete(URL::route('admin.role.destroy', $role))
            ->assertStatus(204);
    }
}
