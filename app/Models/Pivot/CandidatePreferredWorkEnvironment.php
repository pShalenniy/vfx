<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\PreferredWorkEnvironment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidatePreferredWorkEnvironment extends Pivot
{
    protected $table = 'candidate_preferred_work_environment';

    protected $fillable = [
        'candidate_id',
        'preferred_work_environment_id',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'preferred_work_environment_id' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function preferredWorkEnvironment(): BelongsTo
    {
        return $this->belongsTo(
            PreferredWorkEnvironment::class,
            'preferred_work_environment_id',
            'id',
            'preferredWorkEnvironment',
        );
    }
}
