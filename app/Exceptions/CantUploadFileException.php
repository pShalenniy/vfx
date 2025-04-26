<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Support\Facades\Lang;
use RuntimeException;

class CantUploadFileException extends RuntimeException
{
    public function __construct(string $filename)
    {
        parent::__construct(Lang::get('common.exception.cant-upload-file', ['filename' => $filename]));
    }
}
