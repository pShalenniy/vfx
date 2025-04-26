<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Container\Container;

class Factory
{
    protected static array $resolved = [];

    /**
     * @template InstanceType of object
     *
     * @param string<InstanceType> $class
     *
     * @return InstanceType
     */
    public static function make(string $class)
    {
        if (isset(self::$resolved[$class])) {
            return self::$resolved[$class];
        }

        self::$resolved[$class] = Container::getInstance()->make($class);

        return self::$resolved[$class];
    }
}
