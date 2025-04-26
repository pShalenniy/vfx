<?php

declare(strict_types=1);

namespace App\Helpers;

use function is_array;
use function ltrim;
use function parse_url;
use function preg_match;
use function rtrim;

use const null;

class IMDBHelper
{
    public static function getRegexValidation(): string
    {
        return '~^(https?://)?(www\.)?((m|pro).)?imdb\.com/~';
    }

    public static function getIdFromLink(string $link): ?string
    {
        preg_match(
            '~^https?://(?:www\.)?(?:(?:m|pro)\.)?imdb\.com/title/(?<id>tt\d+)~',
            $link,
            $match,
        );

        return $match['id'] ?? null;
    }

    public static function sanitizeLink(?string $link): ?string
    {
        if (null === $link) {
            return null;
        }

        $parsed = parse_url($link);

        if (!is_array($parsed)) {
            return null;
        }

        if (empty($parsed['scheme']) || empty($parsed['host']) || empty($parsed['path'])) {
            return null;
        }

        $url = "{$parsed['scheme']}://{$parsed['host']}";

        return rtrim($url, '/').'/'.ltrim($parsed['path'], '/');
    }
}
