<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

use const false;

class ActiveSession extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'active_sessions';

    protected $keyType = 'string';

    protected $fillable = [
        'token_id',
        'model_id',
        'browser',
        'os',
        'ip',
        'last_activated_at',
    ];

    protected $casts = [
        'token_id' => 'int',
        'model_id' => 'int',
        'model_type' => 'string',
        'browser' => 'string',
        'os' => 'string',
        'ip' => 'string',
        'last_activated_at' => 'datetime',
    ];

    public function accessToken(): BelongsTo
    {
        return $this->belongsTo(PersonalAccessToken::class, 'token_id', 'id', 'accessToken');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function (Model $model) {
            $model->setAttribute($model->getKeyName(), (string) Str::ulid());
        });
    }
}
