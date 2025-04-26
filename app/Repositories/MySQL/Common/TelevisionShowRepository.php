<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\TelevisionShow;
use App\Repositories\Contracts\Common\TelevisionShowRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TelevisionShowRepository implements TelevisionShowRepositoryContract
{
    public function search(Request $request): Collection
    {
        return TelevisionShow::query()
            ->select(['id', 'name', 'season', 'imdb_id'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->orderBy('name')
            ->limit(100)
            ->get();
    }
}
