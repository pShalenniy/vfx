<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Timezone\StoreRequest;
use App\Http\Requests\Admin\Timezone\UpdateRequest;
use App\Http\Resources\Common\TimezoneResource;
use App\Models\Timezone;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class TimezoneController extends Controller
{
    public function store(StoreRequest $request): TimezoneResource
    {
        $timezone = DB::transaction(
            static fn () => Timezone::query()->create($request->validated()),
        );

        return new TimezoneResource($timezone);
    }

    public function update(
        Timezone $timezone,
        UpdateRequest $request,
    ): TimezoneResource {
        DB::transaction(static function () use ($timezone, $request) {
            $timezone->update($request->validated());
        });

        return new TimezoneResource($timezone);
    }

    public function destroy(Timezone $timezone): JsonResponse
    {
        DB::transaction(static function () use ($timezone) {
            $timezone->delete();
        });

        return new JsonResponse([
            'message' => Lang::get('admin/timezone.action.delete.success'),
        ]);
    }
}
