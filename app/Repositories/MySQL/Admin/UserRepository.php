<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\Admin\UserRepositoryContract;
use App\Scopes\OrderBySingleRelationScope;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use function array_filter;

use const null;

class UserRepository implements UserRepositoryContract
{
    public function paginate(Request $request, array $with = []): LengthAwarePaginator
    {
        return User::query()
            ->when($request->get('keyword'), static function ($q, $keyword) {
                $keyword = "{$keyword}%";

                $q->where(static function ($q) use ($keyword) {
                    $q
                        ->where('first_name', 'like', $keyword)
                        ->orWhere('last_name', 'like', $keyword)
                        ->orWhere('email', 'like', $keyword)
                        ->orWhere('company', 'like', $keyword)
                        ->orWhere('job_title', 'like', $keyword);
                });
            })
            ->when($request->get('subscription', []), static function ($q, $filters) {
                $q->whereHas('subscription', static function ($q) use ($filters) {
                    if ($filters['period'] ?? null) {
                        $q->where('subscriptions.period', $filters['period']);
                    }

                    if (!empty($filters['starts_at'])) {
                        [$from, $to] = $filters['starts_at'];

                        $q
                            ->where('subscriptions.starts_at', '>=', Carbon::parse($from))
                            ->where('subscriptions.starts_at', '<=', Carbon::parse($to));
                    }

                    if (!empty($filters['ends_at'])) {
                        [$from, $to] = $filters['ends_at'];

                        $q
                            ->where('subscriptions.ends_at', '>=', Carbon::parse($from))
                            ->where('subscriptions.ends_at', '<=', Carbon::parse($to));
                    }

                    if ($filters['status'] ?? null) {
                        $q->where('subscriptions.status', $filters['status']);
                    }

                    if (isset($filters['contract_signed'])) {
                        $q->where('subscriptions.contract_signed', (bool) $filters['contract_signed']);
                    }

                    if (isset($filters['has_expired'])) {
                        $q
                            ->whereNested(static function ($q) use ($filters) {
                                $q->whereNested(static function ($q) {
                                    $expiredDate = Carbon::now()->subDays(Subscription::EXPIRING_DAYS_PERIOD);
                                    $startOfDay = (string) $expiredDate->clone()->startOfDay();
                                    $endOfDay = (string) $expiredDate->endOfDay();

                                    $q
                                        ->where('subscriptions.ends_at', '>=', $startOfDay)
                                        ->where('subscriptions.ends_at', '<', $endOfDay);
                                })
                                ->orWhere('subscriptions.has_expired', (bool) $filters['has_expired']);
                            });
                    }
                });
            })
            ->when(array_filter($request->get('sort', [])), static function ($q, $sort) {
                if (!isset($sort['by'])) {
                    return null;
                }

                if ('country' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderBySingleRelationScope::class,
                        new OrderBySingleRelationScope(
                            Country::class,
                            'name',
                            'id',
                            $sort['direction'],
                            'country_id',
                        ),
                    );
                }

                if ('region' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderBySingleRelationScope::class,
                        new OrderBySingleRelationScope(
                            Region::class,
                            'name',
                            'id',
                            $sort['direction'],
                            'region_id',
                        ),
                    );
                }

                if ('city' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderBySingleRelationScope::class,
                        new OrderBySingleRelationScope(
                            City::class,
                            'name',
                            'id',
                            $sort['direction'],
                            'city_id',
                        ),
                    );
                }

                if ('subscription.starts_at' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderBySingleRelationScope::class,
                        new OrderBySingleRelationScope(
                            Subscription::class,
                            'starts_at',
                            'user_id',
                            $sort['direction'],
                        ),
                    );
                }

                if ('subscription.period' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderBySingleRelationScope::class,
                        new OrderBySingleRelationScope(
                            Subscription::class,
                            'period',
                            'user_id',
                            $sort['direction'],
                        ),
                    );
                }

                if ('subscription.contract_signed' === $sort['by']) {
                    return $q->withGlobalScope(
                        OrderBySingleRelationScope::class,
                        new OrderBySingleRelationScope(
                            Subscription::class,
                            'contract_signed',
                            'user_id',
                            $sort['direction'],
                        ),
                    );
                }

                return $q->orderBy($sort['by'], $sort['direction'] ?? 'asc');
            })
            ->with($with)
            ->orderBy('created_at')
            ->paginate();
    }
}
