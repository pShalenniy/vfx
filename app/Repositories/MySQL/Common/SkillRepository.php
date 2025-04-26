<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\Skill;
use App\Repositories\Contracts\Common\SkillRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SkillRepository implements SkillRepositoryContract
{
    public function search(Request $request): Collection
    {
        return Skill::query()
            ->select(['id', 'title'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('title', 'like', "{$keyword}%");
            })
            ->orderBy('title')
            ->limit(15)
            ->get();
    }
}
