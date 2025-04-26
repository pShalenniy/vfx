<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Helpers\JsHelper;
use App\Http\Controllers\Traits\HasUserCompanyTrait;
use App\Http\Requests\Client\User\UpdateRequest;
use App\Http\Resources\Client\ShortlistResource;
use App\Http\Resources\Client\SubscriptionResource;
use App\Http\Resources\Client\UserResource;
use App\Repositories\Contracts\Client\ShortlistRepositoryContract;
use App\Repositories\Contracts\Client\SubscriptionRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Throwable;

use const null;

class UserController extends Controller
{
    use HasUserCompanyTrait;

    public function show(Request $request): ViewContract
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $subscription = Factory::make(SubscriptionRepositoryContract::class)->get(
            $request,
        );

        if (null !== $subscription) {
            JsHelper::push([
                'subscription' => new SubscriptionResource($subscription),
            ]);
        }

        JsHelper::push([
            'currentUser' => new UserResource($user),
        ]);

        return View::make('client.pages.user.show');
    }

    public function edit(): ViewContract
    {
        return View::make('client.pages.user.edit');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UpdateRequest $request): UserResource
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $input = $request->validated();

        if ($input['company'] ?? false) {
            $input['company_id'] = $this->getUserCompanyId($input['company'] ?? []);

            unset($input['company']);
        }

        try {
            DB::transaction(static function () use ($user, $input) {
                $user->update($input);
            });
        } catch (Throwable $e) {
            Log::error(
                $e->getMessage(),
                ['controller' => static::class, 'method' => __FUNCTION__],
            );
        }

        return new UserResource($user);
    }

    public function shortlists(Request $request): ViewContract
    {
        JsHelper::push([
            'shortlists' => ShortlistResource::collection(
                Factory::make(ShortlistRepositoryContract::class)->list($request),
            ),
        ]);

        return View::make('client.pages.user.shortlists');
    }
}
