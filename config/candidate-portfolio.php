<?php

declare(strict_types=1);

use App\Parsers\UrlParsers\VideoIdParsers\FacebookComParser;
use App\Parsers\UrlParsers\VideoIdParsers\InstagramComParser;
use App\Parsers\UrlParsers\VideoIdParsers\TikTokComParser;
use App\Parsers\UrlParsers\VideoIdParsers\TwitchTVParser;

return [
    'iframe' => [
        'default' => [
            'attributes' => [
                'height' => 460,
                'width' => 640,
            ],
        ],

        FacebookComParser::KEY => [
            'query' => [
                'height' => 600,
                'width' => 700,
                'show_text' => false,
                't' => 0,
            ],
        ],

        InstagramComParser::KEY => [
            'attributes' => [
                'height' => 800,
                'width' => 450,
            ],
        ],

        TikTokComParser::KEY => [
            'attributes' => [
                'height' => 800,
                'width' => 450,
            ],
        ],

        TwitchTVParser::KEY => [
            'query' => [
                'parent' => parse_url(env('APP_URL', 'localhost'), PHP_URL_HOST),
            ],
        ],
    ],
];
