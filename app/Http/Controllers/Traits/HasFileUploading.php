<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use App\Exceptions\CantUploadFileException;
use Illuminate\Http\UploadedFile;

use function md5;
use function time;

use const false;
use const null;

trait HasFileUploading
{
    protected function storeUploadedFile(
        UploadedFile $file,
        string $path,
        ?string $disk = null,
    ): string {
        $result = $file->storeAs(
            $path,
            md5(((string) time()).$file->getFilename()).'.'.$file->getClientOriginalExtension(),
            ['disk' => $disk],
        );

        if (false === $result) {
            throw new CantUploadFileException($file->getFilename());
        }

        return $result;
    }
}
