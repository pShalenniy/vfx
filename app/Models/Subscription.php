<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Pivot\DepartmentSubscription;
use App\Models\Traits\HasRelationsWithEvents;
use App\Scopes\UserIsNotNullScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use const null;

class Subscription extends Model
{
    use HasFactory;
    use HasRelationsWithEvents;

    public const EXPIRING_DAYS_PERIOD = 28;

    public const PAUSE_COUNT = 2;

    public const PAUSE_MONTH_PERIOD = 3;

    public const PERIOD_THREE_MONTH = 3;
    public const PERIOD_TWELVE_MONTH = 12;

    public const REMIND_PERIOD_ONE_MONTH = 30;
    public const REMIND_PERIOD_TWO_WEEKS = 14;
    public const REMIND_PERIOD_ONE_DAY = 1;

    public const STATUS_ACTIVE = 1;
    public const STATUS_PENDING_DEMO = 2;
    public const STATUS_PENDING_AGREEMENT = 3;
    public const STATUS_PAUSED = 4;
    public const STATUS_CANCELLED = 5;
    public const STATUS_INACTIVE = 6;

    protected $table = 'subscriptions';

    protected $fillable = [
        'status',
        'seats',
        'period',
        'starts_at',
        'ends_at',
        'contract_signed',
    ];

    protected $casts = [
        'user_id' => 'int',
        'status' => 'int',
        'seats' => 'int',
        'period' => 'int',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'contract_signed' => 'bool',
        'has_expired' => 'bool',
        'pause_count' => 'int',
        'reminded_days_ago' => 'int',
    ];

    public function departments(): BelongsToMany
    {
        return $this
            ->belongsToManyWithEvents(
                Department::class,
                'department_subscription',
                'subscription_id',
                'department_id',
                'id',
                'id',
                'departments',
            )
            ->using(DepartmentSubscription::class);
    }

    public function fieldHistories(): HasMany
    {
        return $this->hasMany(SubscriptionFieldHistory::class, 'subscription_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    public function getIsExpiringAttribute(): bool
    {
        $daysForExpiring = !$this->attributes['has_expired'] || null !== $this->attributes['ends_at']
            ? Carbon::parse($this->attributes['ends_at'])->diffInDays(Carbon::now())
            : null;

        return $daysForExpiring && $daysForExpiring <= self::EXPIRING_DAYS_PERIOD;
    }

    public function getHistorableAttributes(): array
    {
        return [
            'status',
            'period',
            'starts_at',
            'ends_at',
            'contract_signed',
            'departments',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new UserIsNotNullScope());
    }
}
