<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\PreferredSector;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidatePreferredSector extends Pivot
{
    protected $table = 'candidate_preferred_sector';

    protected $fillable = [
        'candidate_id',
        'preferred_sector_id',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'preferred_sector_id' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function preferredSector(): BelongsTo
    {
        return $this->belongsTo(PreferredSector::class, 'preferred_sector_id', 'id', 'preferredSector');
    }
}
