<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Helpers\JsHelper;
use App\Http\Resources\Client\ActiveSessionResource;
use App\Http\Resources\Client\ContentDataCollection;
use App\Models\ActiveSession;
use App\Repositories\Contracts\Client\ActiveSessionRepositoryContract;
use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

use const null;

class ActiveSessionController extends Controller
{
    public function view(Request $request): ViewContract
    {
        /** @var \App\Models\Contracts\HasActiveSession&\Laravel\Sanctum\Contracts\HasApiTokens $user */
        $user = $request->user();

        JsHelper::push([
            'activeSessions' => ActiveSessionResource::collection(
                Factory::make(ActiveSessionRepositoryContract::class)->list($user),
            ),
            'page' => new ContentDataCollection(
                Factory::make(ContentDataRepositoryContract::class)->getDataForActiveSessionsPage(),
            ),
            'allowCount' => Config::get('active-session.allow_count'),
        ]);

        return View::make('client.pages.active-sessions');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(Request $request, ActiveSession $activeSession): JsonResponse
    {
        /** @var \App\Models\Contracts\HasActiveSession $user */
        $user = $request->user();

        if (
            $activeSession->getAttribute('model_type') !== $user::class ||
            $user->getKey() !== $activeSession->getAttribute('model_id')
        ) {
            throw ValidationException::withMessages([
                'active-session' => Lang::get('client/active-session.failed'),
            ]);
        }

        DB::transaction(static function () use ($activeSession) {
            $activeSession->accessToken()->delete();
        });

        return new JsonResponse(null, 204);
    }
}
