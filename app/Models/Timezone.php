<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timezone extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'timezones';

    protected $fillable = [
        'tinsel_town_id',
        'name',
        'code',
        'offset',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
        'code' => 'string',
        'offset' => 'string',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'timezone_id', 'id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'timezone_id', 'id');
    }
}
