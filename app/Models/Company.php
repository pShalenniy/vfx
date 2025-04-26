<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Contracts\HasCandidatesRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model implements HasCandidatesRelation
{
    use HasFactory;

    protected $table = 'companies';

    protected $fillable = [
        'tinsel_town_id',
        'name',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'company_id', 'id');
    }
}
