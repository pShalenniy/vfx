<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\PreferredLocation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidatePreferredLocation extends Pivot
{
    protected $table = 'candidate_preferred_location';

    protected $fillable = [
        'candidate_id',
        'preferred_location_id',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'preferred_location_id' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function preferredLocation(): BelongsTo
    {
        return $this->belongsTo(PreferredLocation::class, 'preferred_location_id', 'id', 'preferredLocation');
    }
}
