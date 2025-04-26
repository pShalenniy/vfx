<?php

declare(strict_types=1);

namespace App\Console\Commands\Region;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\Region;
use ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use function array_key_exists;
use function trim;

use const false;

class CollectFromTinselTown extends Command
{
    use DeletingNonExistingRecordsTrait;

    protected $signature = 'region:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect regions from tinsel-town';

    protected array $countriesCache = [];

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('states');

            $iterator = (clone $query)
                ->select(['state_id', 'name', 'country_id'])
                ->lazyById($chunkSize, 'state_id');
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
        $tinselTownRegionIds = [];

        $relations = Config::get('elasticsearch.resources.'.Candidate::class.'.with');

        unset($relations['region']);

        foreach ($iterator as $region) {
            try {
                $regionId = $region->state_id;
                $tinselTownRegionIds[] = $regionId;

                $regionModel = Region::query()
                    ->where('tinsel_town_id', $regionId)
                    ->first();

                if (!$regionModel instanceof Region) {
                    $regionModel = new Region();
                }

                $regionModel->fill([
                    'tinsel_town_id' => $regionId,
                    'name' => trim($region->name),
                    'country_id' => $this->getCountryId($region->country_id),
                ]);

                if (!$regionModel->isDirty()) {
                    continue;
                }

                $regionModel->saveQuietly();

                $candidates = Candidate::query()
                    ->where('region_id', $regionModel->getKey())
                    ->with($relations)
                    ->lazyById($chunkSize);

                foreach ($candidates as $candidate) {
                    $candidate->setRelation('region', $regionModel);

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
                    'id' => $region->state_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        $this->deleteAndSyncRecords($tinselTownRegionIds, Region::class, 'region_id');

        $this->countriesCache = [];

        return self::SUCCESS;
    }

    protected function getCountryId(?int $regionCountryId): int
    {
        if (!array_key_exists($regionCountryId, $this->countriesCache)) {
            $this->countriesCache[$regionCountryId] = Country::query()
                ->where('tinsel_town_id', $regionCountryId)
                ->value('id');
        }

        return $this->countriesCache[$regionCountryId];
    }
}
