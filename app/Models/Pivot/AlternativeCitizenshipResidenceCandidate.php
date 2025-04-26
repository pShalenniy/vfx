<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AlternativeCitizenshipResidenceCandidate extends Pivot
{
    protected $table = 'alternative_citizenship_residence_candidate';

    protected $fillable = [
        'country_id',
        'candidate_id',
    ];

    protected $casts = [
        'country_id' => 'int',
        'candidate_id' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id', 'country');
    }
}
