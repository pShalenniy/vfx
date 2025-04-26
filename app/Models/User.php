<?php

declare(strict_types=1);

namespace App\Models;

use AMgrade\SingleRole\Traits\HasPermission;
use AMgrade\SingleRole\Traits\HasRole;
use App\Models\Contracts\HasActiveSession;
use App\Models\Pivot\UserPreferredJobRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Contracts\HasApiTokens as HasApiTokensContract;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements HasActiveSession, HasApiTokensContract, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasRole;
    use HasPermission;
    use Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_id',
        'country_id',
        'region_id',
        'city_id',
        'job_title',
        'phone_number',
        'has_signatory',
        'password',
    ];

    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'company_id' => 'int',
        'country_id' => 'int',
        'region_id' => 'int',
        'city_id' => 'int',
        'job_title' => 'string',
        'phone_number' => 'string',
        'has_signatory' => 'boolean',
        'password' => 'string',
        'email_verified_at' => 'datetime',
    ];

    public function activeSessions(): MorphMany
    {
        return $this->morphMany(ActiveSession::class, 'model', 'model_type', 'model_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id', 'city');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(UserCompany::class, 'company_id', 'id', 'company');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id', 'country');
    }

    public function preferredJobRoles(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                JobRole::class,
                'user_preferred_job_roles',
                'user_id',
                'job_role_id',
                'id',
                'id',
                'jobRoles',
            )
            ->using(UserPreferredJobRole::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id', 'region');
    }

    public function shortlists(): HasMany
    {
        return $this->hasMany(Shortlist::class, 'user_id', 'id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'user_id', 'id');
    }

    public function viewedCandidates(): HasMany
    {
        return $this->hasMany(ViewedCandidate::class, 'user_id', 'id');
    }

    public function setPasswordAttribute(mixed $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
