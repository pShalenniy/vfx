<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use App\Models\JobRole;
use App\Models\User;

trait HandlePreferredJobRolesTrait
{
    use HasRelationValueIdsTrait;

    protected function handlePreferredJobRoles(
        User $user,
        array $preferredJobRoles,
    ): void {
        $preferredJobRoles = $this->getValueIds(
            $preferredJobRoles,
            JobRole::class,
            'name',
        );

        $user->preferredJobRoles()->sync($preferredJobRoles);
    }
}
