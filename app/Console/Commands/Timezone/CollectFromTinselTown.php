<?php

declare(strict_types=1);

namespace App\Console\Commands\Timezone;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Models\Candidate;
use App\Models\Timezone;
use ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use const false;

class CollectFromTinselTown extends Command
{
    use DeletingNonExistingRecordsTrait;

    protected $signature = 'timezone:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect timezones from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('timezones');

            $iterator = (clone $query)
                ->select(['timezone_id', 'timezone_name', 'abbreviation', 'gmt_offset_name'])
                ->lazyById($chunkSize, 'timezone_id');
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['command' => static::class]);

            return self::FAILURE;
        }

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar($query->count())
            : new NullProgressBar();

        $progressBar->start();

        ElasticsearchService::useBulk();

        $i = 0;
        $tinselTownTimezoneIds = [];

        $relations = Config::get('elasticsearch.resources.'.Candidate::class.'.with');

        unset($relations['timezone']);

        foreach ($iterator as $timezone) {
            try {
                $timezoneId = $timezone->timezone_id;
                $tinselTownTimezoneIds[] = $timezoneId;

                $timezoneModel = Timezone::query()->where('tinsel_town_id', $timezoneId)->first();

                if (!$timezoneModel instanceof Timezone) {
                    $timezoneModel = new Timezone();
                }

                $timezoneModel->fill([
                    'tinsel_town_id' => $timezoneId,
                    'name' => $timezone->timezone_name,
                    'code' => $timezone->abbreviation,
                    'offset' => $timezone->gmt_offset_name,
                ]);

                if (!$timezoneModel->isDirty()) {
                    continue;
                }

                $timezoneModel->saveQuietly();

                $candidates = Candidate::query()
                    ->where('timezone_id', $timezoneModel->getKey())
                    ->with($relations)
                    ->lazyById($chunkSize);

                foreach ($candidates as $candidate) {
                    $candidate->setRelation('timezone', $timezoneModel);

                    ElasticsearchService::update($candidate);

                    $i++;

                    if ($i === $chunkSize) {
                        ElasticsearchService::processBulk();

                        $i = 0;
                    }
                }
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $timezone->timezone_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        $this->deleteAndSyncRecords($tinselTownTimezoneIds, Timezone::class, 'timezone_id');

        return self::SUCCESS;
    }
}
