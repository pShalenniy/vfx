<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use AMgrade\SingleRole\Models\Role;
use App\Http\Requests\Admin\Role\StoreRequest;
use App\Http\Requests\Admin\Role\UpdateRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Repositories\Contracts\Admin\RoleRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use const null;

class RoleController extends Controller
{
    public function list(Request $request): AnonymousResourceCollection
    {
        return RoleResource::collection(
            Factory::make(RoleRepositoryContract::class)->paginate($request, ['permissions']),
        );
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $role = DB::transaction(static function () use ($request) {
            /** @var \AMgrade\SingleRole\Models\Role $role */
            $role = Role::query()->create(['name' => $request->get('name')]);

            $role->permissions()->sync($request->get('permissions', []));

            return $role;
        });

        $role->load('permissions');

        return new JsonResponse(null, 201);
    }

    public function update(Role $role, UpdateRequest $request): JsonResponse
    {
        DB::transaction(static function () use ($role, $request) {
            $role->update(['name' => $request->get('name')]);

            $role->permissions()->sync($request->get('permissions', []));
        });

        $role->load('permissions');

        return new JsonResponse(null, 201);
    }

    public function destroy(Role $role): JsonResponse
    {
        DB::transaction(static function () use ($role) {
            $role->delete();
        });

        return new JsonResponse(null, 204);
    }
}
