<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Parsers\UrlParsers\VideoIdParser;
use Illuminate\Container\Container;
use Tests\TestCase;

class VideoIdParserTest extends TestCase
{
    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testParsers(): void
    {
        $videoIdParserData = require __DIR__.'/../data/video-id-parser/video-id-parser.php';

        $parser = Container::getInstance()->make(VideoIdParser::class);

        foreach ($videoIdParserData as $key => $value) {
            foreach ($value as $item) {
                $this->assertEquals(
                    $item['expected'],
                    $parser->parse($item['given'], [$key]),
                );
            }
        }
    }
}
