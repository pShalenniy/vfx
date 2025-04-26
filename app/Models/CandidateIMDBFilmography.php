<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CandidateIMDBFilmography extends Model
{
    use HasFactory;

    protected $table = 'candidate_imdb_filmographies';

    protected $fillable = [
        'role_type',
        'title',
        'role',
        'year',
        'imdb_id',
        'poster_url',
    ];

    protected $casts = [
        'candidate_id' => 'int',
        'role_type' => 'string',
        'title' => 'string',
        'role' => 'string',
        'year' => 'string',
        'imdb_id' => 'string',
        'poster_url' => 'string',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id', 'candidate');
    }

    public function filmographyEpisodes(): HasMany
    {
        return $this->hasMany(CandidateIMDBFilmographyEpisode::class, 'filmography_id', 'id');
    }
}
