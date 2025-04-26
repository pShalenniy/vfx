<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateSettingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'key' => $this->resource->getAttribute('key'),
            'subject' => $this->resource->getAttribute('subject'),
            'body' => $this->resource->getAttribute('body'),
            'emails' => $this->resource->getAttribute('emails'),
        ];
    }
}
