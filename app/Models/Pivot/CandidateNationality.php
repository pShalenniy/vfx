<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidateNationality extends Pivot
{
    protected $table = 'candidate_nationality';

    protected $fillable = [
        'candidate_id',
        'country_id',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'country_id' => 'int',
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
