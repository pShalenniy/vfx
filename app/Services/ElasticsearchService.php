<?php

declare(strict_types=1);

namespace App\Services;

use App\Console\Helpers\NullProgressBar;
use App\Jobs\Elasticsearch\ElasticsearchIndex;
use App\Jobs\Elasticsearch\ElasticsearchRetry;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\Console\Helper\ProgressBar;

use function class_exists;
use function count;
use function in_array;
use function is_object;

use const false;
use const null;
use const true;

class ElasticsearchService
{
    protected Client $client;

    protected ?string $defaultIndex;

    protected ?string $defaultModel;

    protected array $bulk = [];

    protected bool $useBulk = false;

    /**
     * @throws \Elasticsearch\Common\Exceptions\RuntimeException
     */
    public function __construct()
    {
        $config = Config::get('elasticsearch.settings');

        $this->defaultIndex = $config['defaults']['index'] ?? null;
        $this->defaultModel = $config['defaults']['model'] ?? null;

        $this->setClient();
    }

    public function setClient(): void
    {
        $config = Config::get('elasticsearch.settings');

        $this->client = ClientBuilder::fromConfig(
            Arr::except($config, ['defaults', 'enabled', 'source', 'queue']),
        );
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function useBulk(bool $value = true): void
    {
        $this->useBulk = $value;
    }

    public function getUseBulk(): bool
    {
        return $this->useBulk;
    }

    public function bulk(
        array $body,
        ?string $index = null,
        ?string $refresh = 'false',
    ): array {
        return $this->client->bulk([
            'body' => $body,
            'index' => $this->getIndex($index),
            'refresh' => $refresh ?? 'false',
        ]);
    }

    public function index(
        mixed $id,
        mixed $body = null,
        ?string $index = null,
        ?string $refresh = 'false',
    ): array {
        $model = $this->getModelInstance($id);

        if ($this->useBulk) {
            $this->collectBulk('index', $model);

            return [];
        }

        $params = [
            'index' => $this->getIndex($index),
            'id' => $this->getId($id),
            'body' => $this->getBody($id, $body),
            'refresh' => $refresh ?? 'false',
        ];

        return $this->client->index($params);
    }

    public function indexAll(
        mixed $model,
        ?string $index = null,
        bool $withTrashed = false,
        ?ProgressBar $progressBar = null,
    ): void {
        $progressBar ??= new NullProgressBar();
        $model = $this->getModel($model);
        $index = $this->getIndex($index);

        /** @var \Illuminate\Database\Eloquent\Model $modelObject */
        $modelObject = new $model();

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $modelObject::query()
            ->select([$modelObject->getKeyName()])
            ->when($withTrashed, static function ($q) {
                $q->withoutGlobalScope(new SoftDeletingScope());
            });

        $progressBar->setMaxSteps($query->count());
        $progressBar->start();

        $query->chunkById(
            100,
            static function (EloquentCollection $collection) use ($index, $progressBar) {
                Bus::dispatch(new ElasticsearchIndex($collection, $index));

                $progressBar->advance($collection->count());
            },
        );

        $progressBar->finish();
    }

    public function update(
        mixed $id,
        mixed $body = null,
        ?string $index = null,
        ?string $refresh = 'false',
    ): array {
        $model = $this->getModelInstance($id);

        if ($this->useBulk) {
            $this->collectBulk('update', $model, $body);

            return [];
        }

        $index = $this->getIndex($index);
        $body = $this->getBody($id, $body);
        $id = $this->getId($id);

        $params = [
            'index' => $index,
            'id' => $id,
            'body' => ['doc' => $body],
            'refresh' => $refresh ?? 'false',
            'retry_on_conflict' => 1,
        ];

        try {
            return $this->client->update($params);
        } catch (Missing404Exception) {
            return $this->index($id, $body, $index);
        }
    }

    public function delete(
        mixed $id,
        ?string $index = null,
        ?string $refresh = 'false',
    ): array {
        if ($this->useBulk) {
            $this->collectBulk('delete', $this->getRawModelInstance($id));

            return [];
        }

        $params = [
            'index' => $this->getIndex($index),
            'id' => $this->getId($id),
            'refresh' => $refresh ?? 'false',
        ];

        try {
            return $this->client->delete($params);
        } catch (Missing404Exception) {
            return [];
        }
    }

    public function deleteByQuery(
        array $body,
        ?string $index = null,
    ): array {
        $params = [
            'index' => $this->getIndex($index),
            'body' => $body,
        ];

        try {
            return $this->client->deleteByQuery($params);
        } catch (Missing404Exception) {
            return [];
        }
    }

    public function createIndex(array $body, ?string $index = null): array
    {
        $params = [
            'index' => $index,
            'body' => $body,
        ];

        return $this->client->indices()->create($params);
    }

    public function deleteIndex(?string $index = null): array
    {
        $params = ['index' => $this->getIndex($index)];

        return $this->client->indices()->delete($params);
    }

    public function clearIndex(?string $index = null): array
    {
        $params = [
            'index' => $this->getIndex($index),
            'body' => ['query' => ['match_all' => new stdClass()]],
            'refresh' => true,
            'request_cache' => false,
            'wait_for_completion' => true,
            'conflicts' => 'proceed',
            'requests_per_second' => -1,
        ];

        return $this->client->deleteByQuery($params);
    }

    public function getAllIndices(): array
    {
        return $this->client->cat()->indices();
    }

    public function flushIndex(?string $index = null): void
    {
        $params = ['index' => $this->getIndex($index)];

        $this->client->indices()->flush($params);
    }

    public function get(mixed $id, ?string $index = null): array
    {
        $params = [
            'index' => $this->getIndex($index),
            'id' => $this->getId($id),
        ];

        return $this->client->get($params);
    }

    public function search(
        array $body,
        ?string $index = null,
        array $additionalParams = [],
    ): array {
        $params = [
            'index' => $this->getIndex($index),
            'body' => $body,
        ] + $additionalParams;

        return $this->client->search($params);
    }

    public function searchAll(
        array $body = [],
        ?string $index = null,
        bool $returnOnlyIds = false,
    ): array {
        $items = [];

        $body = [
            'size' => 1000,
            'track_total_hits' => true,
            'sort' => [
                '_id' => 'asc',
            ],
        ] + $body;

        $lastId = null;

        do {
            if (null !== $lastId) {
                $body['search_after'] = [$lastId];
            }

            $results = $this->search($body, $index);
            $total = $results['hits']['total']['value'] ?? 0;

            foreach ($results['hits']['hits'] ?? [] as $hit) {
                $lastId = $hit['_id'];

                if ($returnOnlyIds) {
                    $items[] = $hit['_id'];
                } else {
                    $items[] = $hit;
                }
            }
        } while ($total > count($items));

        return $items;
    }

    public function addMapping(
        array $body,
        ?string $index = null,
    ): array {
        $params = [
            'index' => $this->getIndex($index),
            'body' => $body,
        ];

        return $this->client->indices()->putMapping($params);
    }

    public function explain(
        mixed $id,
        array $body,
        ?string $index = null,
    ): array {
        $params = [
            'index' => $this->getIndex($index),
            'id' => $this->getId($id),
            'body' => $body,
        ];

        return $this->client->explain($params);
    }

    public function getDefaultModel(): string
    {
        return $this->defaultModel;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function collectBulk(string $method, Model $model, ?array $body = []): void
    {
        $method = Str::lower($method);

        if (!in_array($method, ['index', 'create', 'update', 'delete'], true)) {
            throw new InvalidArgumentException(Lang::get('common.exception.elasticsearch.invalid_argument'));
        }

        $this->bulk[] = [$method, $model, $body ?? []];
    }

    public function processBulk(
        ?string $index = null,
        ?string $refresh = 'false',
    ): array {
        $body = [];

        $i = 0;

        foreach ($this->bulk as $item) {
            /** @var \Illuminate\Database\Eloquent\Model $item */
            [$method, $model, $modelBody] = $item;

            if ('create' === $method) {
                $method = 'index';
            } elseif ('update' === $method) {
                $method = empty($modelBody) ? 'index' : 'update';
            }

            if ('update' === $method) {
                $body[$i][] = [
                    $method => [
                        '_id' => $model->getKey(),
                        'retry_on_conflict' => 1,
                    ],
                ];
            } else {
                $body[$i][] = [$method => ['_id' => $model->getKey()]];
            }

            if ('index' === $method) {
                $body[$i][] = $this->getBody($model);
            } elseif ('update' === $method) {
                $body[$i][] = ['doc' => $modelBody ?: $this->getBody($model)];
            }

            // @link https://stackoverflow.com/a/20196343
            if (count($body[$i]) >= 100) {
                $i++;
            }
        }

        $this->clearBulk();

        if (empty($body)) {
            return [];
        }

        $results = [];

        // Don't use array_chunk, because you can lose body of the "index" operation.
        foreach ($body as $chunk) {
            try {
                $results[] = $this->bulk($chunk, $index, $refresh);
            } catch (NoNodesAvailableException) {
                Bus::dispatch(new ElasticsearchRetry('bulk', [$chunk, null, $index]));

                $results[] = [];
            }
        }

        $this->logBulkErrors($results);

        return $results;
    }

    public function clearBulk(): void
    {
        $this->bulk = [];
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function getIndex(?string $index = null): string
    {
        if (null === $index && null === $this->defaultIndex) {
            throw new InvalidArgumentException(Lang::get('common.exception.elasticsearch.invalid_index'));
        }

        return $index ?? $this->defaultIndex;
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function getModel(mixed $id, mixed $model = null): string
    {
        if (null === $model && $id instanceof Model) {
            return $id::class;
        }

        if (null === $model && null === $this->defaultModel) {
            throw new InvalidArgumentException(Lang::get('common.exception.elasticsearch.invalid_model'));
        }

        if (null === $model) {
            return $this->defaultModel;
        }

        return is_object($model) ? $model::class : (string) $model;
    }

    protected function getId(mixed $id): string
    {
        if ($id instanceof Model) {
            return (string) $id->getKey();
        }

        return (string) $id;
    }

    protected function getBody(mixed $id, mixed $body = null): array
    {
        if (null === $body && $id instanceof Model) {
            $body = null !== ($attributes = $this->getModelResourceAttributes($id))
                ? $attributes
                : $id->attributesToArray();
        } else {
            $body = (array) $body;
        }

        unset($body['_id']);

        return $body;
    }

    protected function getModelResourceAttributes(Model $model): ?array
    {
        $resource = Config::get('elasticsearch.resources.'.$model::class.'.resource');

        if (null === $resource || !class_exists($resource)) {
            return null;
        }

        /** @var array $data */
        $data = (new $resource($model))->toArray();

        foreach ($data as $key => $value) {
            if ($value instanceof Collection) {
                $collectionItems = [];

                foreach ($value as $collectionItem) {
                    if (!$collectionItem instanceof Model) {
                        $collectionItems[] = $collectionItem;
                    } else {
                        $collectionItems[] = $this->getModelResourceAttributes($collectionItem)
                            ?? $collectionItem->attributesToArray();
                    }
                }

                $value = $collectionItems;
            } elseif ($value instanceof Model) {
                $attributes = $this->getModelResourceAttributes($value);

                $value = $attributes ?? $value->attributesToArray();
            }

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function getModelInstance(mixed $id): Model
    {
        if ($id instanceof Model) {
            return $id;
        }

        return $this->getModel($id)::query()->findOrFail($id);
    }

    protected function getRawModelInstance(mixed $id): Model
    {
        if ($id instanceof Model) {
            return $id;
        }

        $modelClass = $this->getModel($id);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = new $modelClass();
        $model->forceFill([$model->getKeyName() => $id]);

        return $model;
    }

    protected function logBulkErrors(array $results): void
    {
        $errors = [];

        foreach ($results as $result) {
            if ($result['errors'] ?? false) {
                foreach ($result['items'] as $actions) {
                    foreach ($actions as $item) {
                        if (!isset($item['error'])) {
                            continue;
                        }

                        $errors[$item['error']['type']][] = [$item['_id'] => $item['error']['reason']];
                    }
                }
            }
        }

        if (!empty($errors)) {
            Log::error('[ElasticsearchService][BULK]', $errors);
        }
    }
}
