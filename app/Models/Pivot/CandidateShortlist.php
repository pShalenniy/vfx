<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\Shortlist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidateShortlist extends Pivot
{
    protected $table = 'candidate_shortlist';

    protected $casts = [
        'candidate_id' => 'int',
        'shortlist_id' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function shortlist(): BelongsTo
    {
        return $this->belongsTo(Shortlist::class, 'shortlist_id', 'id', 'shortlist');
    }
}
