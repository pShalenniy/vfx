<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Extensions\Database\Eloquent\Relations\BelongToManyWithEvents;

use const null;

trait HasRelationsWithEvents
{
    public function belongsToManyWithEvents(
        $related,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $relation = null,
    ) {
        if (null === $relation) {
            $relation = $this->guessBelongsToManyRelation();
        }

        $instance = $this->newRelatedInstance($related);

        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey();
        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey();

        if (null === $table) {
            $table = $this->joiningTable($related, $instance);
        }

        return new BelongToManyWithEvents(
            $instance->newQuery(),
            $this,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey ?: $this->getKeyName(),
            $relatedKey ?: $instance->getKeyName(),
            $relation,
        );
    }
}
