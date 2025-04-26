<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StarCandidate extends Model
{
    use HasFactory;

    protected $table = 'star_candidates';

    protected $fillable = [
        'candidate_id',
        'start_period',
        'end_period',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'start_period' => 'datetime',
        'end_period' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }
}
