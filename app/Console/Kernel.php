<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\Award\CollectFromTinselTown::class,
        Commands\Candidate\CollectFromTinselTown::class,
        Commands\Candidate\Purge::class,
        Commands\Candidate\ScrapeIMDB::class,
        Commands\Candidate\ScrapeLinkedin::class,
        Commands\City\CollectFromTinselTown::class,
        // todo: add into schedule these two commands below.
        Commands\City\GetCoordinates::class,
        Commands\City\GetTimezones::class,
        Commands\Company\CollectFromTinselTown::class,
        Commands\Country\CollectFromTinselTown::class,
        Commands\Department\CollectFromTinselTown::class,
        Commands\Elasticsearch\Reindex::class,
        Commands\Elasticsearch\Setup::class,
        Commands\JobRole\CollectFromTinselTown::class,
        Commands\PersonalAccessToken\Clear::class,
        Commands\Region\CollectFromTinselTown::class,
        Commands\Role\Setup::class,
        Commands\Schedule\SequenceRun::class,
        Commands\Skill\CollectFromTinselTown::class,
        Commands\StarCandidate\Clear::class,
        Commands\Subscription\EndPausedPeriodReminder::class,
        Commands\Subscription\RenewExpired::class,
        Commands\Subscription\RenewPaused::class,
        Commands\TelevisionShow\CollectFromTinselTown::class,
        Commands\Test\Migrate::class,
        Commands\Timezone\CollectFromTinselTown::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command(Commands\Schedule\SequenceRun::class, ['cmd' => [
        //     (new Commands\Award\CollectFromTinselTown())->getName(),
        //     (new Commands\Country\CollectFromTinselTown())->getName(),
        //     (new Commands\Region\CollectFromTinselTown())->getName(),
        //     (new Commands\City\CollectFromTinselTown())->getName(),
        //     (new Commands\Company\CollectFromTinselTown())->getName(),
        //     (new Commands\Skill\CollectFromTinselTown())->getName(),
        //     (new Commands\TelevisionShow\CollectFromTinselTown())->getName(),
        //     (new Commands\Timezone\CollectFromTinselTown())->getName(),
        //     (new Commands\JobRole\CollectFromTinselTown())->getName(),
        //     (new Commands\Department\CollectFromTinselTown())->getName(),
        //     (new Commands\Candidate\CollectFromTinselTown())->getName(),
        // ]])
        //     ->everyMinute()
        //     ->runInBackground()
        //     ->withoutOverlapping();

        $schedule->command(Commands\StarCandidate\Clear::class)
            ->twiceDaily()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command(Commands\PersonalAccessToken\Clear::class)
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command(Commands\Subscription\EndPausedPeriodReminder::class)
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command(Commands\Subscription\RenewExpired::class)
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();

        $schedule->command(Commands\Subscription\RenewPaused::class)
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();
    }
}
