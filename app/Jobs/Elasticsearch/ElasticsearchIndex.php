<?php

declare(strict_types=1);

namespace App\Jobs\Elasticsearch;

use App\Jobs\Types\ElasticsearchJob;
use ElasticsearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Config;

use function class_uses;
use function count;
use function in_array;
use function is_a;
use function is_countable;

use const false;
use const null;
use const true;

class ElasticsearchIndex extends ElasticsearchJob
{
    public int $timeout = 1800;

    public function __construct(
        protected Collection $models,
        protected ?string $index = null,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function handle(): void
    {
        if ($this->models->isEmpty()) {
            return;
        }

        ElasticsearchService::useBulk();

        $firstModel = $this->models->first();
        $with = Config::get('elasticsearch.'.$firstModel::class.'.with', []);

        if (!empty($with)) {
            $this->models->load($with);
        }

        /** @var \Illuminate\Database\Eloquent\Model $model */
        foreach ($this->models as $model) {
            ElasticsearchService::index($model, null, $this->index);
        }

        ElasticsearchService::processBulk($this->index);
        ElasticsearchService::useBulk(false);
    }

    protected function restoreCollection($value): Collection
    {
        if (
            !$value->class ||
            0 === (is_countable($value->id) ? count($value->id) : 0)
        ) {
            return new Collection();
        }

        $columns = Config::get("elasticsearch.{$value->class}.columns", ['*']);

        $collection = $this
            ->getQueryForModelRestoration(
                (new $value->class())->setConnection($value->connection),
                $value->id,
            )
            ->useWritePdo()
            ->get($columns ?: ['*']);

        if (
            is_a($value->class, Pivot::class, true) ||
            in_array(AsPivot::class, class_uses($value->class), true)
        ) {
            return $collection;
        }

        $collection = $collection->keyBy(static fn ($item) => $item->getKey());

        $collectionClass = $collection::class;

        $items = [];

        foreach ($value->id as $id) {
            if (empty($collection[$id] ?? null)) {
                continue;
            }

            $items[] = $collection[$id];
        }

        return new $collectionClass($items);
    }
}
