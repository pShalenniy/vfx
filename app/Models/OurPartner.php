<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurPartner extends Model
{
    use HasFactory;

    public const CACHE_KEY_ALL = 'our_partners_all';

    public const STORAGE_DISK = 'public';

    protected $table = 'our_partners';

    protected $fillable = [
        'logo',
    ];

    protected $casts = [
        'logo' => 'string',
    ];
}
