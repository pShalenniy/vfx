<?php

declare(strict_types=1);

namespace App\Http\Controllers\Common;

use App\Http\Resources\Common\TimezoneResource;
use App\Repositories\Contracts\Common\TimezoneRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class TimezoneController extends Controller
{
    public function list(): AnonymousResourceCollection
    {
        return TimezoneResource::collection(
            Factory::make(TimezoneRepositoryContract::class)->all(),
        );
    }
}
