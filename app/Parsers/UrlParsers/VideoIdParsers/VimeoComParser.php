<?php

declare(strict_types=1);

namespace App\Parsers\UrlParsers\VideoIdParsers;

use App\Parsers\UrlParsers\VideoIdParserContract;

use function http_build_query;
use function implode;
use function mb_strlen;
use function mb_strpos;
use function mb_substr;
use function preg_match;
use function sprintf;
use function str_contains;
use function str_starts_with;
use function trim;

use const false;
use const null;

class VimeoComParser implements VideoIdParserContract
{
    public const KEY = 'vimeo.com';

    public function parse(array $parsed, string $url): ?array
    {
        if (!$this->validate($parsed['host'])) {
            return null;
        }

        $parsed['path'] = trim($parsed['path'], '/');

        $pathTypes = [
            'showcase/',
            'manage/showcases/',
            'manage/videos/',
        ];

        foreach ($pathTypes as $type) {
            $result = $this->getFromPath($parsed['path'], $type);

            if (null !== $result) {
                return $result;
            }
        }

        return null;
    }

    public function validate(string $url): bool
    {
        return (bool) preg_match('~(?:www\.)?vimeo\.com~i', $url);
    }

    /**
     * @see https://developer.vimeo.com/player/sdk/embed
     */
    public function getIframeCode(
        string $id,
        string $original,
        array $urlQuery = [],
        array $attributes = [],
        ?string $type = null,
    ): ?string {
        $mapTypes = [
            'videos' => 'https://player.vimeo.com/video/%s',
            'showcases' => 'https://vimeo.com/album/%s/embed',
        ];

        if (null === ($url = $mapTypes[$type] ?? null)) {
            return null;
        }

        $url = sprintf($url, $id);

        if (!empty($urlQuery)) {
            $url .= '?'.http_build_query($urlQuery);
        }

        $string = '<iframe %s />';

        $attributes['src'] = $url;

        $keyedAttributes = [];

        foreach ($attributes as $key => $value) {
            $keyedAttributes[] = "{$key}=\"{$value}\"";
        }

        return sprintf($string, implode(' ', $keyedAttributes));
    }

    protected function getFromPath(string $parsedPath, string $type): ?array
    {
        if ((int) $parsedPath !== 0) {
            $videoId = $parsedPath;
        } else {
            if (!str_starts_with($parsedPath, $type)) {
                return null;
            }

            $videoId = mb_substr(
                $parsedPath,
                mb_strpos($parsedPath, $type) + mb_strlen($type),
            );

            if (empty($videoId)) {
                return null;
            }

            if (false !== ($position = mb_strpos($videoId, '/info'))) {
                $videoId = mb_substr($videoId, 0, $position);
            }
        }

        if (false !== ($typePosition = mb_strpos($type, 'manage/'))) {
            $type = mb_substr($type, $typePosition + mb_strlen('manage/'));
        }

        return [
            'key' => self::KEY,
            'id' => $videoId,
            'type' => str_contains($type, 'showcase') ? 'showcases' : trim($type, '/'),
        ];
    }
}
