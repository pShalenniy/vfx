<?php

declare(strict_types=1);

return [
    'matches' => [
        // List of exact emails that are prohibited.
        'full' => [
            //
        ],

        // List of email domains that are prohibited.
        'domain' => [
            'gmail.com',
            'yahoo.com',
            'outlook.com',
            'mail.com',
            'mail.ru',
            'bk.ru',
            'list.ru',
            'i.ua',
        ],

        // List of regular expressions for checking emails that are prohibited.
        'regexp' => [
            // '~@gmail\.com$~',
        ],
    ],

    'observer' => [
        // The HeimdallObserver passes through the below events for the specified models for
        // emails that match the conditions in the "matches" section above.
        'class' => AMgrade\Heimdall\Observers\HeimdallObserver::class,

        // List of events tracked in the HeimdallObserver.
        'events' => [
            'creating',
            'updating',
        ],

        // List of models for which the observer will be applied.
        // Key is the model name and value is the attribute name.
        'models' => [
            App\Models\User::class => 'email',
        ],
    ],
];
