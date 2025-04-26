<?php

declare(strict_types=1);

namespace App\Http\Resources\Client\Candidate;

use App\Models\UserCompany;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserCompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'name' => $this->resource->getAttribute('name'),
            'url' => $this->resource->getAttribute('url'),
            'logo' => Storage::disk(UserCompany::STORAGE_DISK)->url(
                $this->resource->getAttribute('logo'),
            ),
        ];
    }
}
