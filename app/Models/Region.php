<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'regions';

    protected $fillable = [
        'tinsel_town_id',
        'name',
        'country_id',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
        'country_id' => 'int',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'region_id', 'id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'region_id', 'id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id', 'country');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'region_id', 'id');
    }
}
