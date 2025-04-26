<?php

declare(strict_types=1);

namespace App\Http\Resources\Traits;

use const null;

trait HasCandidatePortfolioUrl
{
    protected function getOriginalPortfolioUrl(?array $portfolioUrl): ?string
    {
        if (empty($portfolioUrl)) {
            return null;
        }

        return $portfolioUrl['original'] ?? null;
    }
}
