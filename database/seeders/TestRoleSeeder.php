<?php

declare(strict_types=1);

namespace Database\Seeders;

use AMgrade\SingleRole\Models\Role;
use Illuminate\Database\Seeder;

class TestRoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getRolesData() as $data) {
            Role::query()->create($data);
        }
    }

    // todo: add real data for roles, also add permissions for roles
    protected function getRolesData(): array
    {
        return [
            ['name' => 'Editor'],
            ['name' => 'Moderator'],
            ['name' => 'Author'],
        ];
    }
}
