<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use ElasticsearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use RuntimeException;

use function is_string;

class ElasticsearchQuery
{
    protected array $filters = [];

    public function __construct(protected Request $request)
    {
    }

    /**
     * @throws \RuntimeException
     */
    public function apply(ElasticsearchFilter|string $filter): self
    {
        if (is_string($filter)) {
            $filter = new $filter();

            if (!$filter instanceof ElasticsearchFilter) {
                throw new RuntimeException(Lang::get('common.exception.elasticsearch.filter_runtime'));
            }
        }

        $this->filters[] = $filter;

        return $this;
    }

    public function getBody(array $body = []): array
    {
        foreach ($this->filters as $filter) {
            $body = $filter->apply($this->request, $body);
        }

        return $body;
    }

    public function get(array $body = []): array
    {
        return ElasticsearchService::search($this->getBody($body));
    }
}
