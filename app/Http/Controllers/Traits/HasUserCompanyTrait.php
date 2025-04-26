<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use App\Exceptions\CantUploadFileException;
use App\Models\UserCompany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use McMatters\Ticl\Client;
use Throwable;

use function basename;
use function file_exists;
use function is_string;
use function mb_stristr;
use function md5;
use function parse_url;
use function preg_match;
use function preg_replace;
use function str_contains;
use function time;

use const null;
use const PHP_URL_HOST;
use const true;

trait HasUserCompanyTrait
{
    use HasFileUploading;

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function getUserCompanyId(array $company): int
    {
        $filePath = is_string($company['logo']) && str_contains($company['logo'], UserCompany::STORAGE_FOLDER)
            ? mb_stristr($company['logo'], UserCompany::STORAGE_FOLDER)
            : $company['logo'];

        if (isset($company['id'])) {
            $previousLogoPath = UserCompany::query()->where('id', $company['id'])->value('logo');
        }

        try {
            $userCompanyId = DB::transaction(function () use ($company, $filePath) {
                if ($company['logo'] instanceof UploadedFile) {
                    $filePath = $this->storeUploadedFile(
                        $company['logo'],
                        UserCompany::STORAGE_FOLDER,
                        UserCompany::STORAGE_DISK,
                    );
                } elseif (
                    is_string($company['logo']) &&
                    !Storage::disk(UserCompany::STORAGE_DISK)->exists($filePath)
                ) {
                    $filePath = $this->storeCompanyLogo($company['logo']);

                    if (null === $filePath) {
                        throw new CantUploadFileException($company['logo']);
                    }
                }

                $userCompany = UserCompany::query()->updateOrCreate(
                    ['url' => $this->getCompanyUrlWithoutWww($company['url'])],
                    [
                        'logo' => $filePath,
                        'name' => $company['name'],
                    ],
                );

                return $userCompany->getKey();
            });
        } catch (Throwable $e) {
            if (isset($filePath) && file_exists($filePath)) {
                Storage::disk(UserCompany::STORAGE_DISK)->delete($filePath);
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

        if (isset($previousLogoPath) && $filePath !== $previousLogoPath) {
            Storage::disk(UserCompany::STORAGE_DISK)->delete($previousLogoPath);
        }

        return $userCompanyId;
    }

    protected function getCompanyUrlWithoutWww(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST) ?: $url;

        return preg_replace('#^www\.#i', '', $host);
    }

    protected function storeCompanyLogo(string $logo): ?string
    {
        $response = (new Client())
            ->withHeaders(['User-Agent' => 'Ticl'])
            ->get($logo);

        $extension = 'png';

        $responseContentType = $response->getHeader('Content-Type');

        if (
            $responseContentType &&
            preg_match('~^image/(?<extension>jpe?g|png|bmp|gif|webp)~', $responseContentType, $match)
        ) {
            $extension = $match['extension'];
        }

        $fileName = md5(((string) time()).mb_stristr(basename($logo), '.', true)).'.'.$extension;
        $filePath = UserCompany::STORAGE_FOLDER."/{$fileName}";

        $result = Storage::disk(UserCompany::STORAGE_DISK)->put(
            $filePath,
            $response->getBody(),
        );

        return $result ? $filePath : null;
    }
}
