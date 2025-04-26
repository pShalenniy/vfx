<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateLinkedinExperienceDetail extends Model
{
    use HasFactory;

    protected $table = 'candidate_linkedin_experience_details';

    protected $fillable = [
        'title',
        'description',
        'location',
        'dates',
        'employment',
    ];

    protected $casts = [
        'experience_id' => 'int',
        'title' => 'string',
        'description' => 'string',
        'location' => 'string',
        'dates' => 'string',
        'employment' => 'string',
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(
            CandidateLinkedinExperience::class,
            'experience_id`',
            'id',
            'experience',
        );
    }
}
