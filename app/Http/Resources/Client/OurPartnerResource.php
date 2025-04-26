<?php

declare(strict_types=1);

namespace App\Http\Resources\Client;

use App\Models\OurPartner;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OurPartnerResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'logo' => Storage::disk(OurPartner::STORAGE_DISK)->url($this->resource->getAttribute('logo')),
        ];
    }
}
