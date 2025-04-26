<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\TelevisionShow;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidateTelevisionShow extends Pivot
{
    protected $table = 'candidate_television_show';

    protected $fillable = [
        'candidate_id',
        'television_show_id',
        'skill_id',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'television_show_id' => 'int',
        'skill_id' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function televisionShow(): BelongsTo
    {
        return $this->belongsTo(TelevisionShow::class, 'television_show_id', 'id', 'televisionShow');
    }
}
