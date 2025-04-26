<?php

declare(strict_types=1);

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class ActiveSessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'ip' => $this->resource->getAttribute('ip'),
            'browser' => $this->resource->getAttribute('browser'),
            'os' => $this->resource->getAttribute('os'),
            'last_activated_at' => $this->resource->getAttribute('last_activated_at')?->format('Y/m/d H:i:s'),
        ];
    }
}
