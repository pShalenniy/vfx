<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\SkillResource;
use App\Repositories\Contracts\Common\SkillRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class SkillController extends Controller
{
    public function search(Request $request): AnonymousResourceCollection
    {
        return SkillResource::collection(
            Factory::make(SkillRepositoryContract::class)->search($request),
        );
    }
}
