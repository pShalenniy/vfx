<?php

declare(strict_types=1);

namespace App\Helpers;

use function rtrim;
use function strtok;

class UrlHelper
{
    public static function rtrim(string $url): string
    {
        return rtrim($url, '/#?');
    }

    public static function stripAfterPath(string $url): string
    {
        return self::rtrim(strtok($url, '?'));
    }
}
