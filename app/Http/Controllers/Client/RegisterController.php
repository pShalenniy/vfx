<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Helpers\JsHelper;
use App\Http\Controllers\Traits\HandlePreferredJobRolesTrait;
use App\Http\Controllers\Traits\HasRelationValueIdsTrait;
use App\Http\Controllers\Traits\HasUserCompanyTrait;
use App\Http\Requests\Register\RegisterRequest;
use App\Http\Resources\Client\ContentDataCollection;
use App\Mail\Common\Register\VerifyEmailMail;
use App\Models\Department;
use App\Models\User;
use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Throwable;

use const null;

class RegisterController extends Controller
{
    use HasRelationValueIdsTrait;
    use HandlePreferredJobRolesTrait;
    use HasUserCompanyTrait;

    public function view(): ViewContract
    {
        return View::make('client.pages.register');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $existingUser = User::query()->where('email', $request->get('email'))->value('id');

        if (null !== $existingUser) {
            throw ValidationException::withMessages([
                'email' => Lang::get('client/register.notification.sign_up.message'),
            ]);
        }

        $input = $request->validated();
        $input['company_id'] = $this->getUserCompanyId($input['company'] ?? []);

        unset($input['company']);

        try {
            $user = DB::transaction(function () use ($request, $input) {
                /** @var \App\Models\User $user */
                $user = User::query()->create($input);

                $this->handlePreferredJobRoles($user, $request->get('preferred_job_roles', []));

                return $user;
            });

            Mail::to($user)->send(new VerifyEmailMail($user));
        } catch (Throwable $e) {
            Log::error(
                $e->getMessage(),
                ['controller' => static::class, 'method' => __FUNCTION__],
            );
        }

        return new JsonResponse(null, 201);
    }

    public function subscription(): ViewContract
    {
        JsHelper::push([
            'departments' => Department::query()
                ->select(['id', 'name'])
                ->with('jobRoles:id,name')
                ->get(),
            'intro' => new ContentDataCollection(
                Factory::make(ContentDataRepositoryContract::class)->getSubscriptionIntro(),
            ),
        ]);

        return View::make('client.pages.subscription.list');
    }

    public function subscriptionThankYou(): ViewContract
    {
        JsHelper::push([
            'text' => new ContentDataCollection(
                Factory::make(ContentDataRepositoryContract::class)->getSubscriptionThankYouText(),
            ),
        ]);

        return View::make('client.pages.subscription.thank-you');
    }
}
