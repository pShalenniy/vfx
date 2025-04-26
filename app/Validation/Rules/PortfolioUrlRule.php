<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Helpers\CandidateHelper;
use App\Parsers\UrlParsers\VideoIdParser;
use Illuminate\Container\Container;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

use function in_array;

use const false;
use const true;

class PortfolioUrlRule implements Rule
{
    public function __construct(protected array $keys = [])
    {
    }

    public function passes($attribute, $value): bool
    {
        if (Container::getInstance()->make(VideoIdParser::class)->validate($value, $this->keys)) {
            return true;
        }

        if (empty($this->keys) || in_array('image', $this->keys, true)) {
            return CandidateHelper::checkPortfolioUrlImage($value);
        }

        return false;
    }

    public function message(): string
    {
        return Lang::get('validation.link', [
            'link' => Lang::get('client/candidate.content_section.portfolio'),
        ]);
    }
}
