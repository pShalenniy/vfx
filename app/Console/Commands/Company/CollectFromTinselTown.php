<?php

declare(strict_types=1);

namespace App\Console\Commands\Company;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Models\Candidate;
use App\Models\Company;
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

    protected $signature = 'company:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect companies from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('companies');

            $iterator = (clone $query)
                ->select(['company_id', 'name'])
                ->lazyById($chunkSize, 'company_id');
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
        $tinselTownCompanyIds = [];

        $relations = Config::get('elasticsearch.resources.'.Candidate::class.'.with');

        unset($relations['company']);

        foreach ($iterator as $company) {
            try {
                $companyId = $company->company_id;
                $tinselTownCompanyIds[] = $companyId;

                $companyModel = Company::query()
                    ->where('tinsel_town_id', $companyId)
                    ->first();

                if (!$companyModel instanceof Company) {
                    $companyModel = new Company();
                }

                $companyModel->fill([
                    'tinsel_town_id' => $companyId,
                    'name' => trim($company->name),
                ]);

                if (!$companyModel->isDirty()) {
                    continue;
                }

                $companyModel->saveQuietly();

                $candidates = Candidate::query()
                    ->where('company_id', $company->company_id)
                    ->with($relations)
                    ->lazyById($chunkSize);

                foreach ($candidates as $candidate) {
                    $candidate->setRelation('company', $companyModel);

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
                    'id' => $company->company_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        $this->deleteAndSyncRecords($tinselTownCompanyIds, Company::class, 'company_id');

        return self::SUCCESS;
    }
}
