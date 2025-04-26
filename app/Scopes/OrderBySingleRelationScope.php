<?php

declare(strict_types=1);

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderBySingleRelationScope implements Scope
{
    public function __construct(
        protected string $model,
        protected string $sortField,
        protected string $field,
        protected string $sortDirection,
        protected string $referenceField = 'id',
    ) {
    }

    public function apply(Builder $builder, Model $model): void
    {
        $builder->orderBy(
            $this->model::query()
                ->select([$this->sortField])
                ->whereColumn("{$model->getTable()}.{$this->referenceField}", $this->field)
                ->orderBy($this->sortField, $this->sortDirection)
                ->limit(1),
            $this->sortDirection,
        );
    }
}
