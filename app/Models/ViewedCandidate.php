<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewedCandidate extends Model
{
    use HasFactory;

    public const VIEWED_COMPANIES_COUNT = 3;

    protected $table = 'viewed_candidates';

    protected $fillable = [
        'candidate_id',
        'user_id',
        'viewed_at',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'user_id' => 'int',
        'viewed_at' => 'datetime',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
