<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'tinsel_town_id',
        'name',
        'region_id',
        'timezone_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
        'region_id' => 'int',
        'timezone_id' => 'int',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'city_id', 'id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id', 'region');
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class, 'timezone_id', 'id', 'timezone');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'city_id', 'id');
    }
}
