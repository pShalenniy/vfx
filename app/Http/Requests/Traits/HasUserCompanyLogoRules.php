<?php

declare(strict_types=1);

namespace App\Http\Requests\Traits;

use App\Validation\Rules\CustomMimesRule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use McMatters\Helpers\Helpers\ServerHelper;

use function is_string;
use function min;

trait HasUserCompanyLogoRules
{
    protected function getUserCompanyLogoRules(): array
    {
        $company = $this->get('company', []);

        if (!isset($company['logo'])) {
            return ['required'];
        }

        if (is_string($company['logo'])) {
            return ['required', 'string', 'url'];
        }

        if ($company['logo'] instanceof UploadedFile) {
            $maxFilesize = min(ServerHelper::getUploadMaxFilesize('kb'), 5120);

            return [
                'required',
                'file',
                "max:{$maxFilesize}",
                new CustomMimesRule(Config::get('file-extensions.picture')),
            ];
        }

        return ['required'];
    }
}
