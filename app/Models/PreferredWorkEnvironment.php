<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use App\Models\Pivot\CandidatePreferredWorkEnvironment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PreferredWorkEnvironment extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'preferred_work_environments';

    protected $fillable = [
        'name',
        'is_other',
    ];

    protected $casts = [
        'name' => 'string',
        'is_other' => 'bool',
    ];

    public function candidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Candidate::class,
                'candidate_preferred_work_environment',
                'preferred_work_environment_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->using(CandidatePreferredWorkEnvironment::class);
    }
}
