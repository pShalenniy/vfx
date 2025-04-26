<?php

declare(strict_types=1);

namespace App\Parsers\UrlParsers\VideoIdParsers;

use App\Parsers\UrlParsers\VideoIdParserContract;
use App\Parsers\UrlParsers\VideoIdParsers\Traits\HasYoutubeComIframeCode;

use function explode;
use function mb_strlen;
use function mb_strpos;
use function mb_substr;
use function preg_match;
use function str_contains;
use function str_starts_with;
use function trim;

use const null;

class YoutubeComParser implements VideoIdParserContract
{
    use HasYoutubeComIframeCode;

    public const KEY = 'youtube.com';

    public function parse(array $parsed, string $url): ?array
    {
        if (!$this->validate($parsed['host'])) {
            return null;
        }

        if (empty($parsed['path'])) {
            return null;
        }

        $parsed['path'] = trim($parsed['path'], '/');

        $methods = ['Playlist', 'Shorts', 'Watch'];

        foreach ($methods as $method) {
            $method = "getFrom{$method}";

            if (null !== ($result = $this->{$method}($parsed))) {
                return $result;
            }
        }

        return null;
    }

    public function validate(string $url): bool
    {
        return (bool) preg_match('~(?:www\.)?(?:m\.)?youtube\.com~i', $url);
    }

    protected function getFromPlaylist(array $parsed): ?array
    {
        if (null === ($parsed['query'] ?? null) || !str_contains($parsed['query'] ?? '', 'list')) {
            return null;
        }

        $playlistId = mb_substr(
            $parsed['query'],
            mb_strpos($parsed['query'], 'list=') + mb_strlen('list='),
        );

        if (empty($playlistId)) {
            return null;
        }

        return [
            'key' => self::KEY,
            'id' => $playlistId,
            'type' => 'playlist',
        ];
    }

    protected function getFromShorts(array $parsed): ?array
    {
        if (!str_starts_with($parsed['path'], 'shorts')) {
            return null;
        }

        $videoId = mb_substr(
            $parsed['path'],
            mb_strpos($parsed['path'], 'shorts/') + mb_strlen('shorts/'),
        );

        if (empty($videoId)) {
            return null;
        }

        return [
            'key' => self::KEY,
            'id' => $videoId,
            'type' => 'shorts',
        ];
    }

    protected function getFromWatch(array $parsed): ?array
    {
        if (!str_starts_with($parsed['path'], 'watch')) {
            return null;
        }

        $query = explode('=', $parsed['query'] ?? '');

        if ('v' === $query[0]) {
            return [
                'key' => self::KEY,
                'id' => $query[1],
                'type' => 'single',
            ];
        }

        return null;
    }
}
