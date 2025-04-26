<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Exceptions\CantUploadFileException;
use App\Http\Controllers\Traits\HasFileUploading;
use App\Http\Requests\Admin\OurPartner\StoreRequest;
use App\Http\Requests\Admin\OurPartner\UpdateRequest;
use App\Http\Resources\Admin\OurPartnerResource;
use App\Models\OurPartner;
use App\Repositories\Contracts\Admin\OurPartnerRepositoryContract;
use App\Repositories\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

use function file_exists;

use const null;

class OurPartnerController extends Controller
{
    use HasFileUploading;

    public function list(): AnonymousResourceCollection
    {
        return OurPartnerResource::collection(
            Factory::make(OurPartnerRepositoryContract::class)->list(),
        );
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $logoFile = $this->storeUploadedFile(
                $request->file('logo'),
                'our-partners',
                OurPartner::STORAGE_DISK,
            );

            DB::transaction(
                static fn () => OurPartner::query()->create(['logo' => $logoFile]),
            );
        } catch (Throwable $e) {
            if (isset($logoFile) && file_exists($logoFile)) {
                Storage::disk(OurPartner::STORAGE_DISK)->delete($logoFile);
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

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(OurPartner $ourPartner, UpdateRequest $request): JsonResponse
    {
        $previousLogo = $ourPartner->getAttribute('logo');

        $file = $request->file('logo');

        try {
            $logoFile = $this->storeUploadedFile(
                $request->file('logo'),
                'our-partners',
                OurPartner::STORAGE_DISK,
            );

            DB::transaction(static function () use ($ourPartner, $logoFile) {
                $ourPartner->update(['logo' => $logoFile]);
            });
        } catch (Throwable $e) {
            if (isset($logoFile) && file_exists($logoFile)) {
                Storage::disk(OurPartner::STORAGE_DISK)->delete($logoFile);
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

        if (null !== $file) {
            Storage::disk(OurPartner::STORAGE_DISK)->delete($previousLogo);
        }

        return new JsonResponse(null, 201);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function destroy(OurPartner $ourPartner): JsonResponse
    {
        $file = $ourPartner->getAttribute('logo');

        try {
            DB::transaction(static function () use ($ourPartner) {
                $ourPartner->delete();
            });

            Storage::disk(OurPartner::STORAGE_DISK)->delete($file);
        } catch (Throwable) {
            throw ValidationException::withMessages([
                'logo' => Lang::get('our-partner.action.create.failed'),
            ]);
        }

        return new JsonResponse(null, 204);
    }
}
