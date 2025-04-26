<?php

declare(strict_types=1);

namespace App\Console\Commands\Country;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Models\Candidate;
use App\Models\Country;
use ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use function trim;

use const false;

class CollectFromTinselTown extends Command
{
    use DeletingNonExistingRecordsTrait;

    protected $signature = 'country:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect countries from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('countries');

            $iterator = (clone $query)
                ->select(['country_id', 'name', 'sortname'])
                ->lazyById($chunkSize, 'country_id');
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
        $tinselTownCountryIds = [];

        $relations = Config::get('elasticsearch.resources.'.Candidate::class.'.with');

        unset($relations['country']);

        foreach ($iterator as $country) {
            try {
                $countryId = $country->country_id;
                $tinselTownCountryIds[] = $countryId;

                $countryModel = Country::query()
                    ->where('tinsel_town_id', $countryId)
                    ->first();

                if (!$countryModel instanceof Country) {
                    $countryModel = new Country();
                }

                $countryModel->fill([
                    'tinsel_town_id' => $countryId,
                    'name' => trim($country->name),
                    'code' => trim($country->sortname),
                ]);

                if (!$countryModel->isDirty()) {
                    continue;
                }

                $countryModel->saveQuietly();

                $candidates = Candidate::query()
                    ->where('country_id', $countryModel->getKey())
                    ->with($relations)
                    ->lazyById($chunkSize);

                foreach ($candidates as $candidate) {
                    $candidate->setRelation('country', $countryModel);

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
                    'id' => $country->country_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        $this->deleteAndSyncRecords($tinselTownCountryIds, Country::class, 'country_id');

        return self::SUCCESS;
    }
}
