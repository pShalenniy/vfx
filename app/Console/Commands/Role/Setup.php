<?php

declare(strict_types=1);

namespace App\Console\Commands\Role;

use AMgrade\SingleRole\Models\Role;
use App\Models\Permission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

use function array_keys;
use function array_search;

use const true;

class Setup extends Command
{
    protected $signature = 'role:setup {--with-detaching}';

    protected $description = 'Setup roles';

    public function handle(): int
    {
        $withDetaching = $this->option('with-detaching');

        foreach (Config::get('single-role.roles') as $roleName => $params) {
            $role = Role::query()->firstOrCreate(
                ['name' => $roleName],
            );

            $rolePermissions = $role->permissions()->pluck('name', 'id')->all();

            foreach ($params['permissions'] as $name => $description) {
                if (!($key = array_search($name, $rolePermissions, true))) {
                    $permission = Permission::query()->firstOrCreate(
                        ['name' => $name],
                        ['description' => $description],
                    );

                    $role->attachPermissions($permission);
                } elseif ($withDetaching) {
                    unset($rolePermissions[$key]);
                }
            }

            if ($withDetaching && $rolePermissions) {
                $role->permissions()->detach(array_keys($rolePermissions));
            }
        }

        $this->info('Roles and permissions have been successfully added');

        return self::SUCCESS;
    }
}
