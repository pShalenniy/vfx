<?php

declare(strict_types=1);

namespace App\Console\Commands\Test;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

use const true;

class Migrate extends Command
{
    protected $signature = 'test:migrate {--seed=} {--seeder=}';

    public function handle(): int
    {
        $files = Finder::create()
            ->in($this->laravel->databasePath('migrations'))
            ->name('/.*create_.*\.php$/')
            ->files()
            ->ignoreDotFiles(true)
            ->ignoreUnreadableDirs()
            ->ignoreVCS(true);

        foreach ($files as $file) {
            $this->call('migrate', ['--path' => $file->getPathname(), '--realpath' => true]);
        }

        if ($this->option('seed')) {
            $seeder = $this->option('seeder') ?? 'DatabaseSeeder';

            $this->call('db:seed', ['--class' => $seeder]);
        }

        return self::SUCCESS;
    }
}
