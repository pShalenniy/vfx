<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\Company;
use App\Repositories\Contracts\Common\CompanyRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CompanyRepository implements CompanyRepositoryContract
{
    public function search(Request $request): Collection
    {
        return Company::query()
            ->select(['id', 'name'])
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $q->where('name', 'like', "{$keyword}%");
            })
            ->orderBy('name')
            ->limit(100)
            ->get();
    }
}
