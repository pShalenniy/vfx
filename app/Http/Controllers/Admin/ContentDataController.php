<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Traits\HasFileUploading;
use App\Http\Requests\Admin\ContentData\SetRequest;
use App\Http\Resources\Admin\ContentDataCollection;
use App\Models\ContentData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use McMatters\Helpers\Helpers\ClassHelper;

use function in_array;
use function preg_replace;
use function str_replace;

use const null;
use const true;

class ContentDataController extends Controller
{
    use HasFileUploading;

    public function list(): ContentDataCollection
    {
        return Cache::rememberForever(
            ContentData::CACHE_KEY_ALL,
            static fn () => new ContentDataCollection(ContentData::query()->get()),
        );
    }

    public function set(SetRequest $request): JsonResponse
    {
        DB::transaction(function () use ($request) {
            $fileFields = Config::get('cms.file-fields');

            foreach ($request->validated() as $key => $value) {
                $key = str_replace('_', '.', $key);

                if (in_array($key, $fileFields['image'], true)) {
                    if ($value instanceof UploadedFile) {
                        $value = $this->storeUploadedFile(
                            $value,
                            'content-data',
                            ContentData::STORAGE_DISK,
                        );
                    } else {
                        $value = preg_replace('#.*storage/#', '', $value);
                    }
                }

                ContentData::query()->updateOrCreate(
                    ['key' => $key],
                    ['value' => $value],
                );
            }
        });

        $keys = ClassHelper::getConstantsStartWith(ContentData::class, 'CACHE_KEY_');

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        return new JsonResponse(null, 201);
    }
}
