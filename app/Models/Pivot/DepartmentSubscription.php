<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Department;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DepartmentSubscription extends Pivot
{
    protected $table = 'department_subscription';

    protected $fillable = [
        'department_id',
        'subscription_id',
    ];

    protected $casts = [
        'department_id' => 'int',
        'subscription_id' => 'int',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id', 'department');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id', 'subscription');
    }
}
