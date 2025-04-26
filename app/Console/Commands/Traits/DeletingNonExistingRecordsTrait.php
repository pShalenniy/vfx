<?php

declare(strict_types=1);

namespace App\Console\Commands\Traits;

use App\Jobs\Client\Candidate\SetNullCandidateRelationJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

use function array_diff;

use const null;

trait DeletingNonExistingRecordsTrait
{
    protected function deleteAndSyncRecords(
        array $tinselTownIds,
        string $model,
        ?string $field = null,
    ): void {
        $modelIds = $model::query()
            ->pluck('tinsel_town_id')
            ->all();

        if ([] !== ($modelChanges = array_diff($modelIds, $tinselTownIds))) {
            $modelItemsIds = $model::query()
                ->whereIn('tinsel_town_id', $modelChanges)
                ->pluck('id')
                ->all();

            foreach ($modelItemsIds as $modelId) {
                try {
                    if ($field) {
                        Bus::dispatchSync(new SetNullCandidateRelationJob($modelId, $field));
                    }
                } catch (Throwable $e) {
                    Log::error($e->getMessage(), [
                        'command' => static::class,
                        'id' => $modelId,
                    ]);
                } finally {
                    $model::query()->whereIn('tinsel_town_id', $modelChanges)->delete();
                }
            }
        }
    }
}
