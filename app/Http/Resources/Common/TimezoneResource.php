<?php

declare(strict_types=1);

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class TimezoneResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'code' => $this->resource->getAttribute('code'),
            'name' => $this->resource->getAttribute('name'),
            'offset' => $this->resource->getAttribute('offset'),
            'created_at' => $this->resource->getAttribute('created_at')?->format('d/m/Y H:i:s'),
        ];
    }
}
