<?php

declare(strict_types=1);

namespace App\Console\Commands\Award;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Models\Award;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use function trim;

class CollectFromTinselTown extends Command
{
    use DeletingNonExistingRecordsTrait;

    protected $signature = 'award:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect awards from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('awards');

            $iterator = (clone $query)
                ->select(['award_id', 'name'])
                ->lazyById($chunkSize, 'award_id');
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['command' => static::class]);

            return self::FAILURE;
        }

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar($query->count())
            : new NullProgressBar();

        $progressBar->start();

        $tinselTownAwardIds = [];

        foreach ($iterator as $award) {
            try {
                $tinselTownAwardIds[] = $award->award_id;

                Award::query()->updateOrCreate(
                    ['tinsel_town_id' => $award->award_id],
                    ['name' => trim($award->name)],
                );
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $award->award_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        $this->deleteAndSyncRecords($tinselTownAwardIds, Award::class);

        return self::SUCCESS;
    }
}
