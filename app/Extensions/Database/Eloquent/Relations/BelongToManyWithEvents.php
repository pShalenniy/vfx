<?php

declare(strict_types=1);

namespace App\Extensions\Database\Eloquent\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use function array_diff;
use function array_keys;
use function array_merge;
use function count;

use const false;
use const true;

class BelongToManyWithEvents extends BelongsToMany
{
    public function sync($ids, $detaching = true)
    {
        $changes = [
            'attached' => [],
            'detached' => [],
            'updated' => [],
        ];

        $current = $this->getCurrentlyAttachedPivots()
            ->pluck($this->relatedPivotKey)
            ->all();

        $records = $this->formatRecordsList($this->parseIds($ids));

        if ($detaching) {
            $detach = array_diff($current, array_keys($records));

            if (count($detach) > 0) {
                $this->detach($detach);

                $changes['detached'] = $this->castKeys($detach);
            }
        }

        $changes = array_merge(
            $changes,
            $this->attachNew($records, $current, false),
        );

        if (
            count($changes['attached']) ||
            count($changes['updated']) ||
            count($changes['detached'])
        ) {
            $this->touchIfTouching();
        }

        $model = $this->getParent();

        if (!($dispatcher = $model::getEventDispatcher())) {
            return $changes;
        }

        $modelClass = $model::class;

        $dispatcher->dispatch(
            "eloquent.saved: {$modelClass}",
            [$model, ['relation' => $this, 'changes' => $changes, 'previous' => $current]],
        );

        return $changes;
    }
}
