<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use App\Models\Pivot\CandidatePreferredLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PreferredLocation extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'preferred_locations';

    protected $fillable = [
        'tinsel_town_id',
        'name',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
    ];

    public function candidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Candidate::class,
                'candidate_preferred_location',
                'preferred_location_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->using(CandidatePreferredLocation::class);
    }
}
