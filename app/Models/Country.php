<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use App\Models\Pivot\AlternativeCitizenshipResidenceCandidate;
use App\Models\Pivot\CandidateNationality;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'countries';

    protected $fillable = [
        'tinsel_town_id',
        'name',
        'code',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
        'code' => 'string',
    ];

    public function alternativeCitizenshipResidenceCandidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Candidate::class,
                'alternative_citizenship_residence_candidate',
                'country_id',
                'candidate_id',
                'id',
                'id',
                'alternativeCitizenshipResidenceCandidates',
            )
            ->using(AlternativeCitizenshipResidenceCandidate::class);
    }

    public function nationalityCandidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Country::class,
                'candidate_nationality',
                'country_id',
                'candidate_id',
                'id',
                'id',
                'nationalities',
            )
            ->using(CandidateNationality::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'country_id', 'id');
    }

    public function regions(): HasMany
    {
        return $this->hasMany(Region::class, 'country_id', 'id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'country_id', 'id');
    }
}
