<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Pivot\CandidateShortlist;
use App\Models\Traits\HasRelationsWithEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shortlist extends Model
{
    use HasFactory;
    use HasRelationsWithEvents;

    protected $table = 'shortlists';

    protected $fillable = [
        'title',
    ];

    protected $casts = [
        'title' => 'string',
        'user_id' => 'int',
    ];

    public function candidates(): BelongsToMany
    {
        return $this
            ->belongsToManyWithEvents(
                Candidate::class,
                'candidate_shortlist',
                'shortlist_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->using(CandidateShortlist::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
