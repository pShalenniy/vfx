<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use App\Models\Pivot\CandidateTelevisionShow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TelevisionShow extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'television_shows';

    protected $fillable = [
        'tinsel_town_id',
        'name',
        'start_year',
        'end_year',
        'season',
        'imdb_id',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
        'start_year' => 'int',
        'end_year' => 'int',
        'season' => 'string',
        'imdb_id' => 'string',
    ];

    public function candidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Candidate::class,
                'candidate_television_show',
                'television_show_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->withPivot('skill_id')
            ->using(CandidateTelevisionShow::class);
    }
}
