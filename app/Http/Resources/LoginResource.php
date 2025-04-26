<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Traits\HasUserCompany;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    use HasUserCompany;

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getKey(),
            'first_name' => $this->resource->getAttribute('first_name'),
            'last_name' => $this->resource->getAttribute('last_name'),
            'email' => $this->resource->getAttribute('email'),
            'company' => $this->getCompanyAttribute(
                $this->resource->getRelationValue('company')?->only(['id', 'name', 'url', 'logo']) ?? [],
            ),
            'city' => $this->resource->getRelationValue('city')?->only(['id', 'name', 'region_id']),
            'region' => $this->resource->getRelationValue('region')?->only(['id', 'name', 'country_id']),
            'country' => $this->resource->getRelationValue('country')?->only(['id', 'name']),
            'job_title' => $this->resource->getAttribute('job_title'),
            'phone_number' => $this->resource->getAttribute('phone_number'),
            'role_id' => $this->resource?->getAttribute('role_id'),
        ];
    }
}
