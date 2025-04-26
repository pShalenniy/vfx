<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\JobRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPreferredJobRole extends Pivot
{
    protected $table = 'user_preferred_job_roles';

    protected $fillable = [
        'job_role_id',
        'user_id',
    ];

    protected $casts = [
        'job_role_id' => 'int',
        'user_id' => 'int',
    ];

    public function preferredJobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class, 'job_role_id', 'id', 'preferredJobRole');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }
}
