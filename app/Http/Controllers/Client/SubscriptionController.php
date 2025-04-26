<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Helpers\JsHelper;
use App\Http\Requests\Client\Subscription\StoreRequest;
use App\Http\Resources\Client\ContentDataCollection;
use App\Mail\Client\Subscription\AdminRequestChangeMail;
use App\Mail\Client\Subscription\AdminRequestPauseMail;
use App\Mail\Client\Subscription\ClientRequestChangeMail;
use App\Mail\Client\Subscription\CreatedMail;
use App\Mail\Client\Subscription\ProcessRequestPauseMail;
use App\Models\EmailTemplateSetting;
use App\Models\Subscription;
use App\Repositories\Contracts\Admin\EmailTemplateSettingRepositoryContract;
use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

use const null;

class SubscriptionController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        Gate::authorize('store', [Subscription::class]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $departments = $request->get('departments', []);

        $subscription = DB::transaction(
            static fn () => $user->subscription()->create(
                $request->validated() + ['status' => Subscription::STATUS_PENDING_DEMO],
            ),
        );

        $subscription->departments()->sync($departments);

        Mail::to($user)->send(new CreatedMail($user));

        return new JsonResponse(null, 201);
    }

    public function requestChange(Request $request): JsonResponse
    {
        $user = $request->user();

        Mail::to($user)->send(new ClientRequestChangeMail());

        $emails = Factory::make(EmailTemplateSettingRepositoryContract::class)->getEmails(
            EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_REQUEST_CHANGE,
        );

        foreach ($emails as $email) {
            Mail::to($email)->send(new AdminRequestChangeMail($user));
        }

        return new JsonResponse(null, 204);
    }

    public function requestPause(Request $request): JsonResponse
    {
        $user = $request->user();

        Gate::authorize(
            'requestPause',
            [Subscription::class, $user->getRelationValue('subscription')],
        );

        Mail::to($user)->send(new ProcessRequestPauseMail());

        $emails = Factory::make(EmailTemplateSettingRepositoryContract::class)->getEmails(
            EmailTemplateSetting::KEY_ADMIN_SUBSCRIPTION_REQUEST_PAUSE,
        );

        foreach ($emails as $email) {
            Mail::to($email)->send(new AdminRequestPauseMail($user));
        }

        return new JsonResponse(null, 204);
    }

    public function inactive(): ViewContract
    {
        JsHelper::push([
            'text' => new ContentDataCollection(
                Factory::make(ContentDataRepositoryContract::class)->getSubscriptionInactiveText(),
            ),
        ]);

        return View::make('client.pages.subscription.inactive');
    }
}
