<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Candidate;
use App\Models\Skill;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidateSkill extends Pivot
{
    public const LEVEL_ADVANCED = 1;
    public const LEVEL_INTERMEDIATE = 2;

    public const TYPE_KEY = 1;
    public const TYPE_WANT_LEARN = 2;
    public const TYPE_WANT_WORK_WITH_TOOLS = 3;

    protected $table = 'candidate_skill';

    protected $fillable = [
        'candidate_id',
        'skill_id',
        'level',
        'type',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'skill_id' => 'int',
        'level' => 'int',
        'type' => 'int',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class, 'skill_id', 'id', 'skill');
    }
}
