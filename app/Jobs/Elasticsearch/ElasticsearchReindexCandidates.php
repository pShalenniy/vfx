<?php

declare(strict_types=1);

namespace App\Jobs\Elasticsearch;

use App\Jobs\Types\ElasticsearchJob;
use App\Models\Candidate;
use App\Models\Contracts\HasCandidatesRelation;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;
use McMatters\Helpers\Helpers\ServerHelper;

use function in_array;

use const null;
use const true;

class ElasticsearchReindexCandidates extends ElasticsearchJob
{
    public int $timeout = 7200;

    public function __construct(
        protected string $method,
        protected HasCandidatesRelation $model,
        protected ?string $index = null,
    ) {
        if (!in_array($method, ['index', 'update', 'delete'], true)) {
            throw new InvalidArgumentException(Lang::get('common.exception.elasticsearch.invalid_method'));
        }
    }

    public function handle(): void
    {
        ServerHelper::longProcesses();

        $index = $this->index ?? null;

        $query = null === $this->model
            ? Candidate::query()
            : $this->model->candidates();

        $query->select(['id'])
            ->chunkById(
                100,
                static function (EloquentCollection $candidates) use ($index) {
                    Bus::dispatch(new ElasticsearchIndex($candidates, $index));
                },
            );
    }
}
