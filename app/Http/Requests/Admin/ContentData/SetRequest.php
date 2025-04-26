<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ContentData;

use App\Models\ContentData;
use App\Validation\Rules\CustomMimesRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;
use McMatters\Helpers\Helpers\ClassHelper;
use McMatters\Helpers\Helpers\ServerHelper;

use function in_array;
use function max;
use function min;
use function str_replace;

use const null;
use const true;

class SetRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [];

        $keys = ClassHelper::getConstantsStartWith(ContentData::class, 'KEY_');
        $config = Config::get('cms');
        $validation = $config['validation'] ?? [];
        $fileFields = $config['file-fields'] ?? [];

        $maxFilesize = ServerHelper::getUploadMaxFilesize('kb');
        $videoMaxFilesize = min($maxFilesize, max($config['files']['sizes']['video'] ?? null, $maxFilesize));
        $imageMaxFilesize = min($maxFilesize, max($config['files']['sizes']['image'] ?? null, $maxFilesize));
        $videoExtensions = $config['files']['extensions']['video'] ?? [];
        $imageExtensions = $config['files']['extensions']['image'] ?? [];

        $data = $this->all();

        foreach ($keys as $key) {
            $fieldRules = $validation[$key] ?? [];

            if ($this->isValidFile($data[$key] ?? null)) {
                if (in_array($key, $fileFields['video'] ?? [], true)) {
                    $fieldRules[] = 'file';
                    $fieldRules[] = "max:{$videoMaxFilesize}";
                    $fieldRules[] = new CustomMimesRule($videoExtensions);
                } elseif (in_array($key, $fileFields['image'] ?? [], true)) {
                    $fieldRules[] = 'file';
                    $fieldRules[] = "max:{$imageMaxFilesize}";
                    $fieldRules[] = new CustomMimesRule($imageExtensions);
                }
            }

            $rules[str_replace('.', '_', $key)] = $fieldRules;
        }

        return $rules;
    }
}
