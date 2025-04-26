<?php

declare(strict_types=1);

namespace App\Parsers\UrlParsers;

use App\Parsers\UrlParsers\VideoIdParsers\FacebookComParser;
use App\Parsers\UrlParsers\VideoIdParsers\FbWatchParser;
use App\Parsers\UrlParsers\VideoIdParsers\InstagramComParser;
use App\Parsers\UrlParsers\VideoIdParsers\TikTokComParser;
use App\Parsers\UrlParsers\VideoIdParsers\TwitchTVParser;
use App\Parsers\UrlParsers\VideoIdParsers\VimeoComParser;
use App\Parsers\UrlParsers\VideoIdParsers\WistiaComParser;
use App\Parsers\UrlParsers\VideoIdParsers\YoutubeComParser;
use App\Parsers\UrlParsers\VideoIdParsers\YoutuBeParser;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;

use function array_keys;
use function array_merge;
use function parse_url;

use const false;
use const null;
use const true;

class VideoIdParser
{
    protected array $parsers = [
        FacebookComParser::KEY => FacebookComParser::class,
        FbWatchParser::KEY => FbWatchParser::class,
        InstagramComParser::KEY => InstagramComParser::class,
        YoutuBeParser::KEY => YoutuBeParser::class,
        YoutubeComParser::KEY => YoutubeComParser::class,
        VimeoComParser::KEY => VimeoComParser::class,
        TikTokComParser::KEY => TikTokComParser::class,
        TwitchTVParser::KEY => TwitchTVParser::class,
        WistiaComParser::KEY => WistiaComParser::class,
    ];

    protected array $resolvedParsers = [];

    public function parse(?string $url, array $keys = []): ?array
    {
        if (null === $url) {
            return null;
        }

        $parsedUrl = parse_url($url);

        if (null === ($parsedUrl['host'] ?? null)) {
            return null;
        }

        foreach ($this->getParsers($keys) as $parser) {
            if (null !== ($result = $parser->parse($parsedUrl, $url))) {
                return array_merge($result, ['original' => $url]);
            }
        }

        return null;
    }

    public function validate(?string $url, array $keys = []): bool
    {
        if (null === $url) {
            return false;
        }

        foreach ($this->getParsers($keys) as $parser) {
            if ($parser->validate($url)) {
                return true;
            }
        }

        return false;
    }

    public function getIframeCode(
        string $key,
        string $id,
        string $original,
        array $urlQuery = [],
        array $attributes = [],
        ?string $type = null,
    ): ?string {
        return $this->getParser($key)->getIframeCode(
            $id,
            $original,
            $urlQuery,
            $attributes,
            $type,
        );
    }

    /**
     * @return \App\Parsers\UrlParsers\VideoIdParserContract[]
     */
    protected function getParsers(array $keys = []): array
    {
        $parsers = [];

        foreach ($keys ?: array_keys($this->parsers) as $key) {
            $parsers[] = $this->getParser($key);
        }

        return $parsers;
    }

    protected function getParser(string $key): VideoIdParserContract
    {
        if (!isset($this->parsers[$key])) {
            throw new InvalidArgumentException(
                Lang::get('common.exception.parser.invalid_name', [
                    'parser_name' => $key,
                ]),
            );
        }

        if (!isset($this->resolvedParsers[$key])) {
            $this->resolvedParsers[$key] = new $this->parsers[$key]();
        }

        return $this->resolvedParsers[$key];
    }
}
