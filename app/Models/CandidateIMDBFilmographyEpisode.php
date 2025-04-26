<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateIMDBFilmographyEpisode extends Model
{
    use HasFactory;

    protected $table = 'candidate_imdb_filmography_episodes';

    protected $fillable = [
        'title',
        'year',
        'details',
        'imdb_id',
    ];

    protected $casts = [
        'filmography_id' => 'int',
        'title' => 'string',
        'year' => 'int',
        'details' => 'string',
        'imdb_id' => 'string',
    ];

    public function filmography(): BelongsTo
    {
        return $this->belongsTo(
            CandidateIMDBFilmography::class,
            'filmography_id',
            'id',
            'filmography',
        );
    }
}
