<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateLinkedinExperience extends Model
{
    use HasFactory;

    protected $table = 'candidate_linkedin_experiences';

    protected $fillable = [
        'image',
        'company',
        'working_period',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'company' => 'string',
        'image' => 'string',
        'working_period' => 'json',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_linkedin_experience_id`', 'id', 'candidate');
    }

    public function details(): HasMany
    {
        return $this->hasMany(CandidateLinkedinExperienceDetail::class, 'experience_id', 'id');
    }
}
