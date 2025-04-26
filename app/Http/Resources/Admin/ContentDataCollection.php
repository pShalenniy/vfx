<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContentDataCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $items = [];

        /** @var \App\Models\ContentData $contentData */
        foreach ($this->resource as $contentData) {
            $items[$contentData->getAttribute('key')] = $contentData->getValueAttribute();
        }

        return $items;
    }
}
