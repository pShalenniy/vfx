<?php

declare(strict_types=1);

namespace App\Helpers;

use Throwable;

use function str_contains;

class ErrorHelper
{
    public static function isDuplicateEntry(Throwable $e): bool
    {
        return str_contains($e->getMessage(), '1062 Duplicate entry');
    }
}
