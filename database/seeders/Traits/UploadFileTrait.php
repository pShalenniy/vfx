<?php

declare(strict_types=1);

namespace Database\Seeders\Traits;

use App\Exceptions\CantUploadFileException;
use Illuminate\Http\UploadedFile;

use const false;

trait UploadFileTrait
{
    protected function storeUploadedFile(UploadedFile $file, string $folder, string $uploadDisk): string
    {
        $result = $file->storeAs($folder, $file->getFilename(), $uploadDisk);

        if (false === $result) {
            throw new CantUploadFileException($file->getFilename());
        }

        return $result;
    }
}
