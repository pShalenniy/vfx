<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCompany extends Model
{
    use HasFactory;

    public const STORAGE_DISK = 'public';
    public const STORAGE_FOLDER = 'user-companies';

    protected $table = 'user_companies';

    protected $fillable = [
        'name',
        'url',
        'logo',
    ];

    protected $casts = [
        'name' => 'string',
        'url' => 'string',
        'logo' => 'string',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }
}
