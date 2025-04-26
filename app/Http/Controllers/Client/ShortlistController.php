<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Requests\Client\Shortlist\StoreRequest;
use App\Http\Requests\Client\Shortlist\SyncCandidateRequest;
use App\Http\Resources\Client\CandidateResource;
use App\Http\Resources\Client\ShortlistResource;
use App\Models\Candidate;
use App\Models\Shortlist;
use App\Repositories\Contracts\Client\CandidateRepositoryContract;
use App\Repositories\Contracts\Client\ShortlistRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use const null;

class ShortlistController extends Controller
{
    public function list(Request $request): AnonymousResourceCollection
    {
        return ShortlistResource::collection(
            Factory::make(ShortlistRepositoryContract::class)->list($request),
        );
    }

    public function store(StoreRequest $request): ShortlistResource
    {
        $user = $request->user();

        $shortlist = DB::transaction(
            static fn () => $user->shortlists()->create($request->validated()),
        );

        return new ShortlistResource($shortlist);
    }

    public function destroy(Shortlist $shortlist): JsonResponse
    {
        Gate::authorize('destroy', [Shortlist::class, $shortlist]);

        DB::transaction(static function () use ($shortlist) {
            $shortlist->delete();
        });

        return new JsonResponse(null, 204);
    }

    public function getCandidates(Shortlist $shortlist): AnonymousResourceCollection
    {
        return CandidateResource::collection(
            Factory::make(CandidateRepositoryContract::class)->listFromShortlist(
                $shortlist,
                [
                    'id',
                    'first_name',
                    'last_name',
                    'company_id',
                    'picture',
                    'next_availability',
                    'slug',
                ],
                ['skills', 'jobRoles'],
            ),
        );
    }

    public function syncCandidate(
        Shortlist $shortlist,
        SyncCandidateRequest $request,
    ): JsonResponse {
        DB::transaction(static function () use ($shortlist, $request) {
            $shortlist->candidates()->sync($request->get('candidates'));
        });

        return new JsonResponse(null, 201);
    }

    public function detachCandidate(
        Shortlist $shortlist,
        Candidate $candidate,
    ): JsonResponse {
        DB::transaction(static function () use ($shortlist, $candidate) {
            $shortlist->candidates()->detach($candidate->getKey());
        });

        return new JsonResponse(null, 204);
    }
}
