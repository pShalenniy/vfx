<?php

declare(strict_types=1);

use App\Enums\CommercialExperience;

return [
    'commercial_experience' => [
        CommercialExperience::YEARS_0_3->value => '0-3 years',
        CommercialExperience::YEARS_3_6->value => '3-6 years',
        CommercialExperience::YEARS_GT6->value => '6+ years',
    ],
];
