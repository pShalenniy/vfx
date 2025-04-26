<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\DepartmentJobRole;
use App\Models\Pivot\UserPreferredJobRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobRole extends Model
{
    use HasFactory;

    protected $table = 'job_roles';

    protected $fillable = [
        'tinsel_town_id',
        'name',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'name' => 'string',
    ];

    public function candidates(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Candidate::class,
                'candidate_job_role',
                'job_role_id',
                'candidate_id',
                'id',
                'id',
                'candidates',
            )
            ->withPivot('type')
            ->using(CandidateJobRole::class);
    }

    public function departments(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Department::class,
                'department_job_role',
                'job_role_id',
                'department_id',
                'id',
                'id',
                'departments',
            )
            ->using(DepartmentJobRole::class);
    }

    public function users(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                User::class,
                'user_preferred_job_roles',
                'job_role_id',
                'user_id',
                'id',
                'id',
                'users',
            )
            ->using(UserPreferredJobRole::class);
    }
}
