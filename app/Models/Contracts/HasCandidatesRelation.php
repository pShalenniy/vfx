<?php

declare(strict_types=1);

namespace App\Models\Contracts;

interface HasCandidatesRelation
{
    public function candidates(): mixed;
}
