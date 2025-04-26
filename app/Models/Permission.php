<?php

declare(strict_types=1);

namespace App\Models;

use AMgrade\SingleRole\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];
}
