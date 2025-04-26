<?php

declare(strict_types=1);

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;
use const null;

class TelevisionShowResource extends JsonResource
{
    public function toArray($request): array
    {
        $showName = $this->resource->getAttribute('name');
        $showSeason = $this->resource->getAttribute('season');

        return [
            'id' => $this->resource->getKey(),
            'name' => $this->getTelevisionShowName($showName, $showSeason),
            'imdb_id' => $this->resource->getAttribute('imdb_id'),
        ];
    }

    protected function getTelevisionShowName(string $showName, ?string $showSeason): string
    {
        if (null === $showSeason) {
            return $showName;
        }

        return "{$showName} {$showSeason}";
    }
}
