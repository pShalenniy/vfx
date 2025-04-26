<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use const null;

class SubscriptionFieldHistory extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $table = 'subscription_field_histories';

    protected $fillable = [
        'field',
        'previous_value',
        'new_value',
    ];

    protected $casts = [
        'subscription_id' => 'int',
        'field' => 'string',
        'previous_value' => 'json',
        'new_value' => 'json',
        'created_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id', 'subscription');
    }
}
