<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Helpers\CandidateHelper;
use App\Helpers\JsHelper;
use App\Helpers\SubscriptionHelper;
use App\Helpers\TimezoneHelper;
use App\Http\Requests\Client\Candidate\ListRequest;
use App\Http\Requests\Client\Candidate\SendMessageRequest;
use App\Http\Resources\Client\CandidateResource;
use App\Http\Resources\Client\ShortlistResource;
use App\Http\Resources\Common\TelevisionShowResource;
use App\Http\Resources\Traits\GetCandidateRelationValues;
use App\Jobs\Client\Candidate\SendMessageJob;
use App\Models\Candidate;
use App\Models\Skill;
use App\Models\TelevisionShow;
use App\Repositories\Contracts\Client\Candidate\ViewedCandidateRepositoryContract;
use App\Repositories\Contracts\Client\CandidateRepositoryContract;
use App\Repositories\Contracts\Client\ShortlistRepositoryContract;
use App\Repositories\Factory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\View;

use const null;

class CandidateController extends Controller
{
    use GetCandidateRelationValues;

    public function listPage(Request $request): ViewContract
    {
        JsHelper::push([
            'departments' => SubscriptionHelper::availableDepartments($request->user()),
            'shortlists' => $this->getShortlists($request),
            'skills' => Skill::query()->select(['id', 'title'])->toBase()->get(),
            'timezones' => TimezoneHelper::getTimezonesList(),
            'televisionShows' => TelevisionShowResource::collection(
                TelevisionShow::query()
                    ->orderBy('name')
                    ->limit(100)
                    ->get(['id', 'name', 'season', 'imdb_id']),
            ),

        ]);

        return View::make('client.pages.candidates');
    }

    public function list(ListRequest $request): AnonymousResourceCollection
    {
        return CandidateResource::collection(
            Factory::make(CandidateRepositoryContract::class)->paginate(
                $request,
                [
                    'id',
                    'picture',
                    'first_name',
                    'last_name',
                    'imdb_link',
                    'linkedin_link',
                    'instagram_link',
                    'twitter_link',
                    'next_availability',
                    'slug',
                    'created_at',
                ],
                [
                    'alternativeCitizenshipResidencies:id,name,code',
                    'awards',
                    'company:id,name',
                    'country:id,name',
                    'city:id,name,region_id,longitude,latitude',
                    'filmographies',
                    'jobRoles',
                    'linkedinExperiences.details',
                    'nationalities:id,name',
                    'preferredLocations:id,name',
                    'preferredSectors:id,name',
                    'preferredWorkEnvironments:id,name',
                    'region:id,name,country_id',
                    'shortlists:id,title,user_id',
                    'skills:id,title',
                    'televisionShows',
                    'timezone:id,name,offset',
                ],
            ),
        );
    }

    public function show(Request $request, Candidate $candidate): ViewContract
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        Factory::make(ViewedCandidateRepositoryContract::class)->markViewed($user, $candidate);

        $candidate->load([
            'linkedinExperiences' => static function ($q) {
                $q->with([
                    'details:id,experience_id,title,description,location,dates,employment',
                ]);
            },
            'filmographies' => static function ($q) {
                $q->with(['filmographyEpisodes:id,filmography_id,title']);
            },
        ]);

        JsHelper::push([
            'candidate' => new CandidateResource($candidate),
            'shortlists' => $this->getShortlists($request),
        ]);

        return View::make('client.pages.candidate.show');
    }

    public function getAlternativeTalents(Candidate $candidate): AnonymousResourceCollection
    {
        $candidate->load(['jobRoles']);

        return CandidateResource::collection(
            Factory::make(CandidateRepositoryContract::class)->getAlternativeTalents($candidate),
        );
    }

    public function sendMessage(Candidate $candidate, SendMessageRequest $request): JsonResponse
    {
        Bus::dispatch(
            new SendMessageJob($candidate, $request->user(), $request->validated()),
        );

        return new JsonResponse(null, 200);
    }

    public function downloadCv(Candidate $candidate): Response
    {
        $candidate->load([
            'awards:id,name',
            'city:id,name',
            'company:id,name',
            'country:id,name',
            'preferredLocations',
            'nationalities:id,name',
            'preferredSectors:id,name',
            'preferredWorkEnvironments:id,name',
            'region:id,name',
            'alternativeCitizenshipResidencies:id,name',
            'televisionShows:id,name',
            'timezone:id,name,offset',
            'skills' => static function ($q) {
                $q->withPivot(['level', 'type']);
            },
            'jobRoles' => static function ($q) {
                $q->withPivot(['type']);
            },
            'filmographies' => static function ($q) {
                $q->with(['filmographyEpisodes:id,filmography_id,title']);
            },
            'linkedinExperiences' => static function ($q) {
                $q->with([
                    'details:id,experience_id,title,description,location,dates,employment',
                ]);
            },
        ]);

        $candidateCv = PDF::loadView('pdf.candidate.cv', [
            'awards' => $this->getAwards($candidate),
            'candidate' => $candidate,
            'jobRoles' => CandidateHelper::getJobRoles($candidate),
            'skills' => $this->getSkills($candidate),
            'salaryRateCurrency' => $this->getSalaryRateCurrencyValue(
                $candidate->getAttribute('salary_rate_currency'),
            ),
            'budgetOfBiggestShow' => $this->getBudgetOfBiggestShowValue(
                $candidate->getAttribute('budget_of_biggest_show'),
            ),
            'televisionShows' => $this->getTelevisionShows($candidate),
        ]);

        return $candidateCv->download("{$candidate->getFullNameAttribute()}CV.pdf");
    }

    protected function getShortlists(Request $request): AnonymousResourceCollection
    {
        return ShortlistResource::collection(
            Factory::make(ShortlistRepositoryContract::class)->list($request),
        );
    }
}
