<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use App\Models\Pivot\CandidatePreferredSector;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PreferredSector extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'preferred_sectors';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string',
    ];

    public function candidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Candidate::class,
                'candidate_preferred_sector',
                'preferred_sector_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->using(CandidatePreferredSector::class);
    }
}
