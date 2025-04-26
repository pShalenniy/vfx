<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Admin;

use App\Models\Skill;
use App\Repositories\Contracts\Admin\SkillRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use function array_filter;

class SkillRepository implements SkillRepositoryContract
{
    public function paginate(Request $request): LengthAwarePaginator
    {
        return Skill::query()
            ->when(array_filter($request->get('sort', [])), static function ($q, $sort) {
                $q->orderBy($sort['by'] ?? 'id', $sort['direction'] ?? 'asc');
            })
            ->orderBy('title')
            ->paginate();
    }

    public function search(Request $request): Collection
    {
        return Skill::query()
            ->select(['id', 'title'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('title', 'like', "{$keyword}%");
            })
            ->orderBy('title')
            ->limit(100)
            ->get();
    }
}
