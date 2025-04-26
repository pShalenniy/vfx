<?php

declare(strict_types=1);

namespace App\Console\Commands\City;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Models\Candidate;
use App\Models\City;
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
use const null;

class CollectFromTinselTown extends Command
{
    use DeletingNonExistingRecordsTrait;

    protected $signature = 'city:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect cities from tinsel-town';

    protected array $regionsCache = [];

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('cities');

            $iterator = (clone $query)
                ->select(['city_id', 'name', 'state_id'])
                ->lazyById($chunkSize, 'city_id');
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
        $tinselTownCityIds = [];

        $relations = Config::get('elasticsearch.resources.'.Candidate::class.'.with');

        unset($relations['city']);

        foreach ($iterator as $city) {
            try {
                $cityId = $city->city_id;
                $tinselTownCityIds[] = $cityId;

                $cityModel = City::query()
                    ->where('tinsel_town_id', $cityId)
                    ->first();

                if (!$cityModel instanceof City) {
                    $cityModel = new City();
                }

                if (null === ($regionId = $this->getRegionId($city->state_id))) {
                    continue;
                }

                $cityModel->forceFill([
                    'tinsel_town_id' => $cityId,
                    'name' => trim($city->name),
                    'region_id' => $regionId,
                ]);

                if (!$cityModel->isDirty()) {
                    continue;
                }

                $cityModel->saveQuietly();

                $candidates = Candidate::query()
                    ->where('city_id', $cityModel->getKey())
                    ->with($relations)
                    ->lazyById();

                foreach ($candidates as $candidate) {
                    $candidate->setRelation('city', $cityModel);

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
                    'id' => $city->city_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        $this->deleteAndSyncRecords($tinselTownCityIds, City::class, 'city_id');

        $this->regionsCache = [];

        return self::SUCCESS;
    }

    protected function getRegionId(?int $cityRegionId): ?int
    {
        if (!array_key_exists($cityRegionId, $this->regionsCache)) {
            $this->regionsCache[$cityRegionId] = Region::query()
                ->where('tinsel_town_id', $cityRegionId)
                ->value('id');
        }

        return $this->regionsCache[$cityRegionId];
    }
}
