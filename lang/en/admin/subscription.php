<?php

declare(strict_types=1);

use App\Models\Subscription;

return [
    'contract_signed' => [
        'yes' => 'Yes',
        'no' => 'No',
    ],
    'constants' => [
        'period' => [
            Subscription::PERIOD_THREE_MONTH => '3 month',
            Subscription::PERIOD_TWELVE_MONTH => '12 month',
        ],
        'status' => [
            Subscription::STATUS_ACTIVE => 'Active',
            Subscription::STATUS_PENDING_DEMO => 'Pending demo',
            Subscription::STATUS_PENDING_AGREEMENT => 'Pending agreement',
            Subscription::STATUS_PAUSED => 'Paused',
            Subscription::STATUS_CANCELLED => 'Cancelled',
            Subscription::STATUS_INACTIVE => 'Inactive',
        ],
    ],
];
