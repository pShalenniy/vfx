<?php

declare(strict_types=1);

namespace App\Console\Commands\JobRole;

use App\Console\Commands\Traits\DeletingNonExistingRecordsTrait;
use App\Console\Helpers\NullProgressBar;
use App\Models\Candidate;
use App\Models\JobRole;
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

    protected $signature = 'job-role:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect job roles from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('roles');

            $iterator = (clone $query)
                ->select(['role_id', 'name'])
                ->lazyById($chunkSize, 'role_id');
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

        $relations = Config::get('elasticsearch.resources.'.Candidate::class.'.with');

        $tinselTownJobRoleIds = [];

        foreach ($iterator as $jobRole) {
            try {
                $jobRoleId = $jobRole->role_id;
                $jobRoleName = trim($jobRole->name);
                $tinselTownJobRoleIds[] = $jobRoleId;

                $jobRoleModel = JobRole::query()
                    ->where('tinsel_town_id', $jobRoleId)
                    ->orWhere('name', $jobRoleName)
                    ->first();

                if (!$jobRoleModel instanceof JobRole) {
                    $jobRoleModel = new JobRole();
                }

                $jobRoleModel->fill([
                    'tinsel_town_id' => $jobRoleId,
                    'name' => $jobRoleName,
                ]);

                if (!$jobRoleModel->isDirty()) {
                    continue;
                }

                $jobRoleModel->saveQuietly();

                $candidates = Candidate::query()
                    ->join('candidate_job_role', 'candidates.id', '=', 'candidate_job_role.candidate_id')
                    ->where('candidate_job_role.job_role_id', $jobRoleModel->getKey())
                    ->with($relations)
                    ->lazyById($chunkSize);

                foreach ($candidates as $candidate) {
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
                    'id' => $jobRole->role_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        $this->deleteAndSyncRecords($tinselTownJobRoleIds, JobRole::class);

        return self::SUCCESS;
    }
}
