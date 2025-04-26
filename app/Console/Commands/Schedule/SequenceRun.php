<?php

declare(strict_types=1);

namespace App\Console\Commands\Schedule;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Log;
use McMatters\Helpers\Helpers\ServerHelper;
use Symfony\Component\Console\Input\StringInput;

use const null;

class SequenceRun extends Command
{
    protected $signature = 'schedule:sequence-run {cmd*}';

    protected $description = 'Run schedule commands in sequence';

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(): int
    {
        $results = [];

        $commands = $this->argument('cmd');

        Log::info('[SequenceRun]Start', ['commands' => $commands]);

        ServerHelper::longProcesses();

        foreach ($commands as $command) {
            /** @var \App\Console\Kernel $kernel */
            $kernel = $this->laravel->make(Kernel::class);
            $results[] = $kernel->handle(new StringInput($command), $this->getOutput());
        }

        Log::info('[SequenceRun]Finish', ['results' => $results]);

        foreach ($results as $result) {
            if (null !== $result && self::SUCCESS !== $result) {
                return $result;
            }
        }

        return self::SUCCESS;
    }
}
