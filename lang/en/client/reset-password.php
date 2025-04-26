<?php

declare(strict_types=1);

return [
    'form' => [
        'title' => 'Reset your password',
        'password' => [
            'label' => 'Password',
            'placeholder' => 'Password',
        ],
        'password_confirmation' => [
            'label' => 'Password confirmation',
            'placeholder' => 'Password confirmation',
        ],
        'submit_button' => [
            'title' => 'Reset >',
        ],
        'link' => [
            'login' => 'Return to login',
        ],
    ],
    'notification' => [
        'success' => 'Password was successfully reset.',
        'failed' => 'Something went wrong, please try again.',
    ],
];
