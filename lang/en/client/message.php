<?php

declare(strict_types=1);

return [
    'modal' => [
        'title' => 'Message',
        'form' => [
            'from' => 'From',
            'to' => 'To',
            'subject' => 'Subject',
            'message' => 'Message',
        ],
        'buttons' => [
            'send' => 'Send >',
            'return_to_candidate' => 'Return to candidate',
        ],
        'action' => [
            'send' => [
                'success' => 'The message for candidate has been successfully sent.',
            ],
        ],
    ],
];
