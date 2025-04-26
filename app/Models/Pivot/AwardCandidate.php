<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Award;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AwardCandidate extends Pivot
{
    public const RESULT_NOMINATED = 1;
    public const RESULT_WON = 2;

    protected $table = 'award_candidate';

    protected $fillable = [
        'award_id',
        'candidate_id',
        'television_show_id',
        'result',
    ];

    protected $casts = [
        'award_id' => 'int',
        'candidate_id' => 'int',
        'television_show_id' => 'int',
        'result' => 'int',
    ];

    public function award(): BelongsTo
    {
        return $this->belongsTo(Award::class, 'award_id', 'id', 'award');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }
}
