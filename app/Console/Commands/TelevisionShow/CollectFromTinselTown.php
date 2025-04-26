<?php

declare(strict_types=1);

namespace App\Console\Commands\TelevisionShow;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Helpers\IMDBHelper;
use App\Models\TelevisionShow;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use function trim;

class CollectFromTinselTown extends Command
{
    use DeletingNonExistingRecordsTrait;

    protected $signature = 'television-show:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect television shows from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('shows');

            $iterator = (clone $query)
                ->selectRaw('MIN(`show_id`) AS `show_id`, `name`, `start_year`, `end_year`, `season`, `url`')
                ->groupBy(['name', 'start_year', 'end_year', 'season', 'url'])
                ->lazyById($chunkSize, 'show_id');
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['command' => static::class]);

            return self::FAILURE;
        }

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar($query->count())
            : new NullProgressBar();

        $progressBar->start();

        $tinselTownTelevisionShowIds = [];

        foreach ($iterator as $televisionShow) {
            try {
                $tinselTownTelevisionShowIds[] = $televisionShow->show_id;

                TelevisionShow::query()->updateOrCreate(
                    ['tinsel_town_id' => $televisionShow->show_id],
                    [
                        'name' => trim($televisionShow->name),
                        'start_year' => $televisionShow->start_year,
                        'end_year' => $televisionShow->end_year,
                        'season' => $televisionShow->season,
                        'imdb_id' => IMDBHelper::getIdFromLink($televisionShow->url),
                    ],
                );
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $televisionShow->show_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        $this->deleteAndSyncRecords($tinselTownTelevisionShowIds, TelevisionShow::class);

        return self::SUCCESS;
    }
}
