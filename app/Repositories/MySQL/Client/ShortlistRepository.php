<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Client;

use App\Repositories\Contracts\Client\ShortlistRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use const null;

class ShortlistRepository implements ShortlistRepositoryContract
{
    public function list(Request $request): Collection
    {
        $shortlists = new Collection();

        /** @var \App\Models\User|null $user */
        if (null !== ($user = $request->user())) {
            $shortlists = $user->shortlists()
                ->select(['id', 'title', 'user_id', 'created_at'])
                ->with(['candidates' => static function ($q) {
                    $q->with(['company']);
                }])
                ->get();
        }

        return $shortlists;
    }
}
