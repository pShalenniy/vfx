<?php

declare(strict_types=1);

namespace App\Parsers\UrlParsers\VideoIdParsers;

use App\Parsers\UrlParsers\VideoIdParserContract;
use App\Parsers\UrlParsers\VideoIdParsers\Traits\HasYoutubeComIframeCode;

use function preg_match;
use function trim;

use const null;

class YoutuBeParser implements VideoIdParserContract
{
    use HasYoutubeComIframeCode;

    public const KEY = 'youtu.be';

    public function parse(array $parsed, string $url): ?array
    {
        if (!$this->validate($parsed['host'])) {
            return null;
        }

        $parsed['path'] = trim($parsed['path'], '/');

        if (empty($parsed['path'])) {
            return null;
        }

        return [
            'key' => self::KEY,
            'id' => trim($parsed['path'], '/'),
            'type' => 'single',
        ];
    }

    public function validate(string $url): bool
    {
        return (bool) preg_match('~(?:www\.)?youtu\.be~i', $url);
    }
}
