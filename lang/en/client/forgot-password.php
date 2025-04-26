<?php

declare(strict_types=1);

return [
    'form' => [
        'title' => 'Reset your password',
        'subtitle' => 'We\'ll email you instructions to reset your password.',
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Email',
        ],
        'submit_button' => [
            'title' => 'Reset >',
        ],
        'link' => [
            'login' => 'Return to login',
        ],
    ],
    'notification' => [
        'success' => 'We will send you an email with password link if this email exists in our database.',
        'failed' => 'Something went wrong, please try again.',
    ],
];
