<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EmailTemplateSetting\UpdateRequest;
use App\Http\Resources\Admin\EmailTemplateSettingResource;
use App\Models\EmailTemplateSetting;
use App\Repositories\Contracts\Admin\EmailTemplateSettingRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use const null;

class EmailTemplateSettingController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        return EmailTemplateSettingResource::collection(
            Factory::make(EmailTemplateSettingRepositoryContract::class)->list([
                'id',
                'key',
                'subject',
                'body',
                'emails',
            ]),
        );
    }

    public function update(
        EmailTemplateSetting $emailTemplateSetting,
        UpdateRequest $request,
    ): JsonResponse {
        DB::transaction(static function () use ($emailTemplateSetting, $request) {
            $emailTemplateSetting->update($request->validated());
        });

        return new JsonResponse(null, 201);
    }
}
