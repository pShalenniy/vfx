<?php

declare(strict_types=1);

namespace App\Helpers;

use InvalidArgumentException;

use function microtime;

use const null;
use const true;

class TimerHelper
{
    protected static array $timers = [];

    public static function start(string $name): void
    {
        self::$timers[$name] = microtime(true);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function slice(string $name): float
    {
        if (null === ($timer = self::$timers[$name] ?? null)) {
            throw new InvalidArgumentException('Timer does not exist');
        }

        return microtime(true) - $timer;
    }

    public static function stop(string $name): float
    {
        $result = self::slice($name);

        unset(self::$timers[$name]);

        return $result;
    }
}
