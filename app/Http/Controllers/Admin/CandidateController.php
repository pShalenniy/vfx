<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CantUploadFileException;
use App\Helpers\PasswordHelper;
use App\Http\Controllers\Traits\HasCandidateRelationsTrait;
use App\Http\Controllers\Traits\HasFileUploading;
use App\Http\Requests\Admin\Candidate\ListRequest;
use App\Http\Requests\Admin\Candidate\MarkStarredRequest;
use App\Http\Requests\Admin\Candidate\StoreRequest;
use App\Http\Requests\Admin\Candidate\UpdateRequest;
use App\Http\Resources\Admin\CandidateResource;
use App\Mail\Admin\Candidate\CreatedMail;
use App\Models\Candidate;
use App\Models\StarCandidate;
use App\Repositories\Contracts\Admin\CandidateRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Throwable;

use function file_exists;

use const null;

class CandidateController extends Controller
{
    use HasCandidateRelationsTrait;
    use HasFileUploading;

    public function list(ListRequest $request): AnonymousResourceCollection
    {
        return CandidateResource::collection(
            Factory::make(CandidateRepositoryContract::class)->paginate($request, [
                'awards:id,name',
                'company',
                'country:id,name',
                'city:id,name,region_id,longitude,latitude',
                'region:id,name,country_id',
                'alternativeCitizenshipResidencies',
                'televisionShows',
                'preferredSectors:id,name',
                'preferredLocations:id,name',
                'skills',
                'timezone',
                'jobRoles',
                'preferredWorkEnvironments:id,name',
                'nationalities:id,name',
            ]),
        );
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $input = $this->getValidatedInput($request->validated());

        $file = $request->file('picture');

        try {
            if (null !== $file) {
                $input['picture'] = $this->storeUploadedFile(
                    $file,
                    'candidates',
                    Candidate::STORAGE_DISK,
                );
            }

            $password = PasswordHelper::generate();

            $input['source'] = Candidate::SOURCE_DATABASE;
            $input['password'] = $password;

            $candidate = DB::transaction(function () use ($input, $request) {
                /** @var \App\Models\Candidate $candidate */
                $candidate = Candidate::query()->create($input);

                $this->handleRelations($candidate, $request);

                return $candidate;
            });

            Mail::to($candidate)->send(
                new CreatedMail(
                    $password,
                    $candidate->getAttribute('email'),
                    URL::route('login.view'),
                ),
            );
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

        return new JsonResponse(null, 201);
    }

    public function markStarred(MarkStarredRequest $request, Candidate $candidate): JsonResponse
    {
        DB::transaction(
            static fn () => StarCandidate::query()->create([
                'candidate_id' => $candidate->getKey(),
                'start_period' => $request->get('start_period'),
                'end_period' => $request->get('end_period'),
            ]),
        );

        return new JsonResponse(null, 201);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UpdateRequest $request, Candidate $candidate): JsonResponse
    {
        $input = $this->getValidatedInput($request->validated());

        $previousPicture = $candidate->getAttribute('picture');

        $file = $request->file('picture');

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

        if (null !== $file && null !== $previousPicture) {
            Storage::disk(Candidate::STORAGE_DISK)->delete($previousPicture);
        }

        return new JsonResponse(null, 201);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(Candidate $candidate): JsonResponse
    {
        $picture = $candidate->getAttribute('picture');

        try {
            DB::transaction(static function () use ($candidate) {
                $candidate->delete();
            });

            if (null !== $picture) {
                Storage::disk(Candidate::STORAGE_DISK)->delete($picture);
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['controller' => static::class, 'method' => __FUNCTION__]);

            throw ValidationException::withMessages([
                'picture' => Lang::get('admin/candidate.action.delete.failed'),
            ]);
        }

        return new JsonResponse(null, 204);
    }
}
