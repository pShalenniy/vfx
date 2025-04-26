<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Pivot\DepartmentJobRole;
use App\Models\Pivot\DepartmentSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'tinsel_town_id',
        'name',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
    ];

    public function jobRoles(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                JobRole::class,
                'department_job_role',
                'department_id',
                'job_role_id',
                'id',
                'id',
                'jobRoles',
            )
            ->using(DepartmentJobRole::class);
    }

    public function subscriptions(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Subscription::class,
                'department_subscription',
                'department_id',
                'subscription_id',
                'id',
                'id',
                'subscriptions',
            )
            ->using(DepartmentSubscription::class);
    }
}
