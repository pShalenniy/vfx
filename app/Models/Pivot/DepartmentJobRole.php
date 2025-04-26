<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Department;
use App\Models\JobRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DepartmentJobRole extends Pivot
{
    protected $table = 'department_job_role';

    protected $fillable = [
        'department_id',
        'job_role_id',
    ];

    protected $casts = [
        'department_id' => 'int',
        'job_role_id' => 'int',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id', 'department');
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class, 'job_role_id', 'id', 'jobRole');
    }
}
