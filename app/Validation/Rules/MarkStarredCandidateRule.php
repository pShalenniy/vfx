<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Models\StarCandidate;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

use const null;
use const true;

class MarkStarredCandidateRule implements Rule
{
    public function __construct(protected array $input)
    {
    }

    public function passes($attribute, $value): bool
    {
        if (empty($this->input['end_period'])) {
            return true;
        }

        return null === StarCandidate::query()
                ->whereNested(static function ($q) use ($value) {
                    $q->where('start_period', '<=', $value)
                        ->where('end_period', '>=', $value);
                })
                ->whereNested(function ($q) {
                    $q->where('start_period', '<=', $this->input['end_period'])
                        ->where('end_period', '>=', $this->input['end_period']);
                })
                ->toBase()
                ->first(['id']);
    }

    public function message(): string
    {
        return Lang::get('validation.period_overlap');
    }
}
