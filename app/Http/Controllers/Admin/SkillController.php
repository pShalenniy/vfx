<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Skill\StoreRequest;
use App\Http\Requests\Admin\Skill\UpdateRequest;
use App\Http\Resources\Common\SkillResource;
use App\Models\Skill;
use App\Repositories\Contracts\Admin\SkillRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

use const null;

class SkillController extends Controller
{
    public function list(Request $request): AnonymousResourceCollection
    {
        return SkillResource::collection(
            Factory::make(SkillRepositoryContract::class)->paginate($request),
        );
    }

    public function search(Request $request): AnonymousResourceCollection
    {
        return SkillResource::collection(
            Factory::make(SkillRepositoryContract::class)->search($request),
        );
    }

    public function store(StoreRequest $request): JsonResponse
    {
        DB::transaction(
            static fn () => Skill::query()->create($request->validated()),
        );

        return new JsonResponse(null, 201);
    }

    public function update(Skill $skill, UpdateRequest $request): JsonResponse
    {
        DB::transaction(static function () use ($skill, $request) {
            $skill->update($request->validated());
        });

        return new JsonResponse(null, 201);
    }

    public function destroy(Skill $skill): JsonResponse
    {
        DB::transaction(static function () use ($skill) {
            $skill->delete();
        });

        return new JsonResponse(null, 204);
    }
}
