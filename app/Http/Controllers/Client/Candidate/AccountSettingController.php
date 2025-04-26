<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client\Candidate;

use App\Exceptions\CantUploadFileException;
use App\Helpers\CandidateHelper;
use App\Helpers\JsHelper;
use App\Helpers\TimezoneHelper;
use App\Http\Controllers\Traits\HasCandidateRelationsTrait;
use App\Http\Controllers\Traits\HasFileUploading;
use App\Http\Requests\Client\Candidate\AccountSetting\UpdateRequest;
use App\Http\Resources\Client\Candidate\AccountSettingsResource;
use App\Http\Resources\Client\Candidate\UserCompanyResource;
use App\Models\Candidate;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Repositories\Contracts\Client\Candidate\ViewedCandidateRepositoryContract;
use App\Repositories\Factory;
use Carbon\Carbon;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Throwable;

use function file_exists;

use const false;
use const null;

class AccountSettingController extends Controller
{
    use HasCandidateRelationsTrait;
    use HasFileUploading;

    public function show(Request $request): ViewContract
    {
        /** @var \App\Models\Candidate $candidate */
        $candidate = $request->user();

        JsHelper::push([
            'candidate' => new AccountSettingsResource($candidate),
            'commercialExperiences' => [
                'values' => CandidateHelper::getCommercialExperienceYearsRangeList(),
                'maxYear' => CandidateHelper::getCommercialExperienceMaxYear(),
                'minYear' => CandidateHelper::getCommercialExperienceMinYear(),
            ],
            'preferredWorkEnvironments' => PreferredWorkEnvironment::query()
                ->where('is_other', false)
                ->limit(100)
                ->toBase()
                ->get(['id', 'name']),
            'preferredSectors' => PreferredSector::query()->orderBy('name')->toBase()->get(['id', 'name']),
            'timezones' => TimezoneHelper::getTimezonesList(),
            'viewedCompanies' => UserCompanyResource::collection(
                Factory::make(ViewedCandidateRepositoryContract::class)->list(
                    $candidate,
                    ['id', 'name', 'logo', 'url'],
                ),
            ),
        ]);

        return View::make('client.pages.candidate.account-settings');
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UpdateRequest $request): AccountSettingsResource
    {
        /** @var \App\Models\Candidate $candidate */
        $candidate = $request->user();

        $file = $request->file('picture');

        $input = $this->getValidatedInput($request->validated());

        try {
            if ($file instanceof UploadedFile) {
                $input['picture'] = $this->storeUploadedFile(
                    $file,
                    'candidates',
                    Candidate::STORAGE_DISK,
                );
            } else {
                unset($input['picture']);
            }

            if ('' === ($input['password'] ?? '')) {
                unset($input['password']);
            }

            if ($input['commercial_experience'] ?? null) {
                $input['commercial_experience'] = Carbon::parse($input['commercial_experience']);
            }

            DB::transaction(function () use ($candidate, $input, $request) {
                $candidate->update($input);

                $this->handleRelations($candidate, $request);
            });
        } catch (Throwable $e) {
            if (isset($input['picture']) && file_exists($input['picture'])) {
                Storage::disk(Candidate::STORAGE_DISK)->delete($input['picture']);
            }

            if ($e instanceof CantUploadFileException) {
                throw ValidationException::withMessages([
                    'file' => Lang::get('common.exception.cant-upload-file'),
                ]);
            }

            throw ValidationException::withMessages([
                'file' => Lang::get('common.exception.database'),
            ]);
        }

        $candidate->load([
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
        ]);

        return new AccountSettingsResource($candidate);
    }
}
