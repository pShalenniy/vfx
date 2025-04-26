<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

trait HasRelationValueIdsTrait
{
    protected function getValueId(
        array $item,
        string $model,
        string $key,
        array $additionalField = [],
    ): int {
        if (!isset($item['id'])) {
            $modelItem = $model::query()->firstOrCreate([$key => $item[$key]]);

            if ($modelItem->wasRecentlyCreated && !empty($additionalField)) {
                $modelItem->forceFill($additionalField)->save();
            }

            return $modelItem->getKey();
        }

        return (int) $item['id'];
    }

    protected function getValueIds(
        array $items,
        string $model,
        string $key,
        array $additional = [],
        array $additionalKeys = [],
        array $additionalField = [],
    ): array {
        $itemIds = [];

        foreach ($items as $item) {
            $id = $this->getValueId($item, $model, $key, $additionalField);

            foreach ($additionalKeys as $additionalKey) {
                if (isset($item[$additionalKey])) {
                    $additional[$additionalKey] = $item[$additionalKey];
                }
            }

            if (!empty($additional)) {
                $itemIds[$id] = $additional;
            } else {
                $itemIds[] = $id;
            }
        }

        return $itemIds;
    }
}
