<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use App\Models\Pivot\CandidateSkill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = [
        'tinsel_town_id',
        'title',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'title' => 'string',
    ];

    public function candidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Candidate::class,
                'candidate_skill',
                'skill_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->withPivot(['level', 'type'])
            ->using(CandidateSkill::class);
    }
}
