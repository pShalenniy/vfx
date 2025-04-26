<?php

declare(strict_types=1);

namespace App\Models;

use AMgrade\SingleRole\Models\Role as BaseRole;

class Role extends BaseRole
{
    public const NAME_SUPER_ADMIN = 'super-admin';

    protected $table = 'roles';
}
