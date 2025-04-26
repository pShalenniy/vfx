<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderByManyToManyRelationScope implements Scope
{
    public function __construct(
        protected string $model,
        protected string $pivotTable,
        protected string $table,
        protected string $field,
        protected string $sortDirection,
    ) {
    }

    public function apply(Builder $builder, Model $model): void
    {
        $groupedField = "{$this->table}.id";

        $builder->orderBy(
            $this->model::query()
                ->select(['id'])
                ->join($this->pivotTable, "{$this->pivotTable}.{$this->field}", '=', $groupedField)
                ->whereColumn("{$this->pivotTable}.candidate_id", 'candidates.id')
                ->groupBy($groupedField)
                ->limit(1),
            $this->sortDirection,
        );
    }
}
