<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\JobRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidateJobRole extends Pivot
{
    public const TYPE_CURRENT = 1;
    public const TYPE_DESIRED = 2;
    public const TYPE_NEXT_PROMOTION = 3;

    protected $table = 'candidate_job_role';

    protected $fillable = [
        'candidate_id',
        'job_role_id',
        'type',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'job_role_id' => 'int',
        'type' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class, 'job_role_id', 'id', 'jobRole');
    }
}
