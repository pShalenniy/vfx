<?php

declare(strict_types=1);

namespace App\Http\Resources\Client;

use App\Models\Department;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'departments' => $this->resource
                ->getRelationValue('departments')
                ?->map(static fn (Department $department) => $department->only(['id', 'name'])),
            'starts_at' => $this->resource->getAttribute('starts_at')?->format('Y-m-d'),
            'ends_at' => $this->resource->getAttribute('ends_at')?->format('Y-m-d'),
            'is_expiring' => $this->resource->getIsExpiringAttribute(),
            'has_expired' => $this->resource->getAttribute('has_expired'),
            'pause_count' => $this->resource->getAttribute('pause_count'),
        ];
    }
}
