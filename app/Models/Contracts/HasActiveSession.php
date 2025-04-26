<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasActiveSession
{
    public function activeSessions(): MorphMany;
}
