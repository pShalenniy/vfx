<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Helpers\PasswordHelper;
use App\Http\Controllers\Traits\HandlePreferredJobRolesTrait;
use App\Http\Controllers\Traits\HasRelationValueIdsTrait;
use App\Http\Controllers\Traits\HasUserCompanyTrait;
use App\Http\Requests\Admin\User\DestroyRequest;
use App\Http\Requests\Admin\User\ListRequest;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Http\Resources\Admin\UserResource;
use App\Mail\Admin\User\CreatedMail;
use App\Mail\Admin\User\DeletedMail;
use App\Mail\Client\Subscription\RequestPauseMail;
use App\Mail\Common\Register\VerifyEmailMail;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\Admin\UserRepositoryContract;
use App\Repositories\Factory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use const null;

class UserController extends Controller
{
    use HandlePreferredJobRolesTrait;
    use HasRelationValueIdsTrait;
    use HasUserCompanyTrait;

    public function list(ListRequest $request): AnonymousResourceCollection
    {
        return UserResource::collection(
            Factory::make(UserRepositoryContract::class)->paginate(
                $request,
                [
                    'subscription' => static function ($q) {
                        $q->with([
                            'fieldHistories' => static function ($q) {
                                $q->orderByDesc('created_at');
                            },
                            'departments:id,name',
                        ]);
                    },
                    'company',
                    'country:id,name',
                    'region:id,name,country_id',
                    'city:id,name,region_id',
                    'preferredJobRoles',
                ],
            ),
        );
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $password = $request->get('password') ?: PasswordHelper::generate();
        $input = $request->validated();

        $input['company_id'] = $this->getUserCompanyId($input['company'] ?? []);

        unset($input['company']);

        $isVerified = $request->boolean('is_verified');

        $user = DB::transaction(function () use ($input, $password, $isVerified) {
            $user = (new User($input))->forceFill([
                'password' => $password,
                'email_verified_at' => $isVerified ? Carbon::now() : null,
            ]);

            $user->save();

            $this->handleUserSubscription($user, $input['subscription'] ?? []);

            $this->handlePreferredJobRoles($user, $input['preferred_job_roles'] ?? []);

            return $user;
        });

        if ($request->boolean('notify_user')) {
            Mail::to($user)->send(new CreatedMail($password, $user->getAttribute('email')));
        }

        if (!$isVerified) {
            Mail::to($user)->send(new VerifyEmailMail($user));
        }

        return new JsonResponse(null, 201);
    }

    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $input = $request->validated();

        $input['company_id'] = $this->getUserCompanyId($input['company'] ?? []);

        unset($input['company']);

        DB::transaction(function () use ($user, $input) {
            $user->fill($input)->forceFill(Arr::only($input, ['password']));

            $user->save();

            $this->handleUserSubscription($user, $input['subscription'] ?? []);

            $this->handlePreferredJobRoles($user, $input['preferred_job_roles'] ?? []);
        });

        return new JsonResponse(null, 201);
    }

    public function destroy(DestroyRequest $request, User $user): JsonResponse
    {
        DB::transaction(static function () use ($user, $request) {
            if ($request->boolean('should_notify')) {
                Mail::to($user)->send(new DeletedMail());
            }

            $user->delete();
        });

        return new JsonResponse(null, 204);
    }

    protected function getUserSubscriptionData(
        ?Subscription $subscription,
        array $subscriptionData,
    ): array {
        if (empty($subscriptionData)) {
            return $subscriptionData;
        }

        if (
            Subscription::STATUS_ACTIVE === ($subscriptionData['status'] ?? null) &&
            (
                null === $subscription ||
                $subscription->getAttribute('status') !== $subscriptionData['status'] ||
                $subscription->getAttribute('period') !== $subscriptionData['period']
            )
        ) {
            $subscriptionData['starts_at'] = Carbon::now();

            if (Subscription::PERIOD_THREE_MONTH === $subscriptionData['period']) {
                $subscriptionData['ends_at'] = Carbon::now()->addMonths(3);
            } elseif (Subscription::PERIOD_TWELVE_MONTH === $subscriptionData['period']) {
                $subscriptionData['ends_at'] = Carbon::now()->addYear();
            }
        }

        return $subscriptionData;
    }

    protected function handleUserSubscription(
        User $user,
        array $subscription,
    ): void {
        $subscriptionData = $this->getUserSubscriptionData(
            $user->getRelationValue('subscription'),
            $subscription,
        );

        if (empty($subscriptionData)) {
            return;
        }

        /** @var \App\Models\Subscription $subscription */
        $subscription = $user->subscription()->updateOrCreate(
            [],
            $subscriptionData,
        );

        $subscription->departments()->sync($subscriptionData['departments'] ?? []);

        if (
            !$subscription->wasRecentlyCreated &&
            $subscription->getAttribute('status') === Subscription::STATUS_PAUSED
        ) {
            $pauseCount = (int) $subscription->getAttribute('pause_count') + 1;

            $subscription->forceFill(['pause_count' => $pauseCount])->save();

            Mail::to($user)->send(new RequestPauseMail($user));
        }
    }
}
