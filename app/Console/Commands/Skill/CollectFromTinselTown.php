<?php

declare(strict_types=1);

namespace App\Console\Commands\Skill;

use App\Console\Helpers\NullProgressBar;
use App\Models\Candidate;
use App\Models\Skill;
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
    protected $signature = 'skill:collect:tinsel-town {--with-progress-bar}';

    protected $description = 'Collect skills from tinsel-town';

    public function handle(): int
    {
        $chunkSize = 500;

        try {
            $query = DB::connection('tinseltown_mysql')->table('keywords');

            $iterator = (clone $query)
                ->select(['keyword_id', 'name'])
                ->lazyById($chunkSize, 'keyword_id');
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

        foreach ($iterator as $skill) {
            try {
                $skillId = $skill->keyword_id;
                $skillName = trim($skill->name);

                $skillModel = Skill::query()
                    ->where('tinsel_town_id', $skillId)
                    ->orWhere('title', $skillName)
                    ->first();

                if (!$skillModel instanceof Skill) {
                    $skillModel = new Skill();
                }

                $skillModel->fill([
                    'tinsel_town_id' => $skillId,
                    'title' => $skillName,
                ]);

                if (!$skillModel->isDirty()) {
                    continue;
                }

                $skillModel->saveQuietly();

                $candidates = Candidate::query()
                    ->join('candidate_skill', 'candidates.id', '=', 'candidate_skill.candidate_id')
                    ->where('candidate_skill.skill_id', $skillModel->getKey())
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
                    'id' => $skill->keyword_id,
                ]);
            } finally {
                $progressBar->advance();
            }
        }

        $progressBar->finish();

        ElasticsearchService::processBulk();
        ElasticsearchService::useBulk(false);

        return self::SUCCESS;
    }
}
