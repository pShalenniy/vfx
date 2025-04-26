<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ReflectionClass;
use Throwable;

abstract class Job implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels {
        __unserialize as unserializeModel;
    }

    public int $timeout = 60;

    public function __unserialize(array $values): void
    {
        $this->unserializeModel($values);
    }

    public static function getQueueName(): string
    {
        try {
            $reflection = new ReflectionClass(static::class);

            $properties = $reflection->getDefaultProperties();

            return $properties['queue'] ?? 'default';
        } catch (Throwable) {
            return 'default';
        }
    }
}
