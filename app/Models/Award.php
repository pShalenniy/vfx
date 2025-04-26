<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use App\Models\Pivot\AwardCandidate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Award extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'awards';

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
                'award_candidate',
                'award_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->withPivot(['television_show_id', 'result'])
            ->using(AwardCandidate::class);
    }
}
