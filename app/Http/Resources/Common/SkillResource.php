<?php

declare(strict_types=1);

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'tinsel_town_id' => $this->resource->getAttribute('tinsel_town_id'),
            'title' => $this->resource->getAttribute('title'),
            'created_at' => $this->resource->getAttribute('created_at')?->format('d/m/Y H:i:s'),
        ];
    }
}
