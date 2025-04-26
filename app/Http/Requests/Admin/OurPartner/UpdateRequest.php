<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\OurPartner;

use App\Validation\Rules\CustomMimesRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use McMatters\Helpers\Helpers\ServerHelper;

use function min;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $maxFilesize = min(ServerHelper::getUploadMaxFilesize('kb'), 5120);

        return [
            'logo' => [
                'required',
                'file',
                "max:{$maxFilesize}",
                new CustomMimesRule(Config::get('cms.files.extensions.image')),
            ],
        ];
    }
}
