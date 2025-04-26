<?php

declare(strict_types=1);

namespace App\Http\Resources\Client;

use App\Helpers\CandidateHelper;
use App\Models\Candidate;
use App\Models\Pivot\CandidateJobRole;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ShortlistResource extends JsonResource
{
    public function toArray($request): array
    {
        $candidates = $this->resource->relationLoaded('candidates')
            ? $this->resource->getRelationValue('candidates')->load(['company', 'jobRoles'])
            : $this->resource->candidates(['company:id,name', 'jobRoles:id,name'])->get();

        return [
            'id' => $this->resource->getKey(),
            'title' => $this->resource->getAttribute('title'),
            'created_at' => $this->resource->getAttribute('created_at')->format('Y-m-d'),
            'candidates_count' => $candidates->count(),
            'candidates' => $candidates
                ->map(static function (Candidate $candidate) {
                    $jobRoles = CandidateHelper::getJobRoles($candidate);
                    $picture = $candidate->getAttribute('picture');

                    $attributes = $candidate->only(['id', 'slug']);
                    $attributes += [
                        'full_name' => $candidate->getFullNameAttribute(),
                        'company' => $candidate->getRelationValue('company')?->only(['id', 'name']),
                        'next_availability' => $candidate->getAttribute('next_availability')?->format('Y-m-d'),
                        'current_job_roles' => $jobRoles[CandidateJobRole::TYPE_CURRENT] ?? [],
                        'picture' => $picture
                            ? Storage::disk(Candidate::STORAGE_DISK)->url($picture)
                            : Candidate::DEFAULT_PICTURE_PATH,
                    ];

                    return $attributes;
                }),
        ];
    }
}
