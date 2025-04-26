<?php

declare(strict_types=1);

namespace App\Console\Commands\Department;

use App\Console\Helpers\NullProgressBar;
use App\Models\Department;
use App\Models\JobRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use function trim;

use const null;

class CollectFromTinselTown extends Command
{
    protected $signature = 'department:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect departments from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('departments');

            $iterator = (clone $query)
                ->select(['department_id', 'name'])
                ->lazyById($chunkSize, 'department_id');
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['command' => static::class]);

            return self::FAILURE;
        }

        $progressBar = $this->option('with-progress-bar')
            ? $this->output->createProgressBar($query->count())
            : new NullProgressBar();

        $progressBar->start();

        foreach ($iterator as $department) {
            try {
                $departmentModel = Department::query()
                    ->where('tinsel_town_id', $department->department_id)
                    ->whereNested(static function ($q) use ($department) {
                        $q
                            ->where('name', trim($department->name))
                            ->whereNested(static function ($q) use ($department) {
                                $q
                                    ->where('tinsel_town_id', '!=', $department->department_id)
                                    ->orWhereNull('tinsel_town_id');
                            });
                    }, 'or')
                    ->first();

                if (!$departmentModel instanceof Department) {
                    /** @var \App\Models\Department $departmentModel */
                    $departmentModel = Department::query()->create([
                        'tinsel_town_id' => $department->department_id,
                        'name' => trim($department->name),
                    ]);
                }

                $this->handleJobRoles($department, $departmentModel);
            } catch (Throwable $e) {
                Log::error($e->getMessage(), [
                    'command' => static::class,
                    'id' => $department->department_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        return self::SUCCESS;
    }

    protected function handleJobRoles(object $departmentItem, Department $department): void
    {
        $departmentJobRoles = [];

        $jobRoles = DB::connection('tinseltown_mysql')
            ->table('department_roles')
            ->select(['department_role_id', 'department_id', 'role_id'])
            ->where('department_id', $departmentItem->department_id)
            ->get();

        foreach ($jobRoles as $jobRole) {
            $jobRoleId = JobRole::query()
                ->where('tinsel_town_id', $jobRole->role_id)
                ->value('id');

            if (null === $jobRoleId) {
                continue;
            }

            if (isset($departmentJobRoles[$jobRoleId])) {
                continue;
            }

            $departmentJobRoles[] = $jobRoleId;
        }

        $department->jobRoles()->sync($departmentJobRoles);
    }
}
