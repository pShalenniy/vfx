<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Parsers\UrlParsers\VideoIdParsers\FacebookComParser;
use App\Parsers\UrlParsers\VideoIdParsers\InstagramComParser;
use App\Parsers\UrlParsers\VideoIdParsers\TikTokComParser;
use App\Parsers\UrlParsers\VideoIdParsers\TwitchTVParser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

use function explode;
use function method_exists;
use function ucfirst;

use const null;

class VideoIdParserHelper
{
    public static array $iframeConfig = [];

    public static function getIframeDataByKey(string $key): array
    {
        $keyPaths = explode('.', $key);

        $key = '';

        foreach ($keyPaths as $keyPath) {
            $key .= ucfirst(Str::camel($keyPath));
        }

        $method = "get{$key}Data";

        return method_exists(static::class, $method)
            ? self::{$method}()
            : [[], self::getKeyedAttributes()];
    }

    protected static function getIframeConfig(?string $key = null): array
    {
        if (empty(self::$iframeConfig)) {
            self::$iframeConfig = Config::get('candidate-portfolio.iframe');
        }

        return $key ? (self::$iframeConfig[$key] ?? []) : self::$iframeConfig;
    }

    protected static function getFacebookComData(): array
    {
        $iframeConfig = self::getIframeConfig(FacebookComParser::KEY);

        return [
            $iframeConfig['query'],
            self::getKeyedAttributes(),
        ];
    }

    protected static function getFbWatchData(): array
    {
        return self::getFacebookComData();
    }

    protected static function getInstagramComData(): array
    {
        $iframeConfig = self::getIframeConfig(InstagramComParser::KEY);

        return [
            [],
            self::getKeyedAttributes(
                $iframeConfig['attributes']['height'],
                $iframeConfig['attributes']['width'],
            ),
        ];
    }

    protected static function getTikTokComData(): array
    {
        $iframeConfig = self::getIframeConfig(TikTokComParser::KEY);

        return [
            [],
            self::getKeyedAttributes(
                $iframeConfig['attributes']['height'],
                $iframeConfig['attributes']['width'],
            ),
        ];
    }

    protected static function getTwitchTvData(): array
    {
        $iframeConfig = self::getIframeConfig(TwitchTVParser::KEY);

        return [
            $iframeConfig['query'],
            self::getKeyedAttributes(),
        ];
    }

    protected static function getKeyedAttributes(?int $height = null, ?int $width = null): array
    {
        $iframeConfig = self::getIframeConfig('default');

        return [
            'height' => $height ?? $iframeConfig['attributes']['height'],
            'width' => $width ?? $iframeConfig['attributes']['width'],
            'frameborder' => '0',
            'allow' => 'autoplay; fullscreen; clipboard-write; encrypted-media; picture-in-picture',
            'allowfullscreen' => 'true',
            'allowtransparency' => 'true',
        ];
    }
}
