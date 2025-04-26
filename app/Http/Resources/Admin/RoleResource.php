<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use AMgrade\SingleRole\Models\Permission;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->getAttribute('name'),
            'permissions' => $this->resource
                ->getRelationValue('permissions')
                ?->map(static fn (Permission $permission) => $permission->only(['id', 'description'])),
        ];
    }
}
