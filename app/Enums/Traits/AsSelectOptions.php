<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use BackedEnum;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

use function class_basename;

trait AsSelectOptions
{
    public static function asSelectOptions(): array
    {
        $class = Str::snake(class_basename(static::class));

        $options = [];

        foreach (self::cases() as $case) {
            if ($case instanceof BackedEnum) {
                $key = "enums.{$class}.{$case->value}";

                $value = $case->value;
            } else {
                $key = "enums.{$class}.{$case->name}";

                $value = $case->name;
            }

            $name = Lang::has($key) ? Lang::get($key) : $case->name;

            $options[$case->name] = ['name' => $name, 'value' => $value];
        }

        return $options;
    }
}
