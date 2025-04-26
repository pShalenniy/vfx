<?php

declare(strict_types=1);

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->getAttribute('name'),
            'timezone' => $this->resource?->getRelationValue('timezone')?->only(['id', 'name', 'offset']),
        ];
    }
}
