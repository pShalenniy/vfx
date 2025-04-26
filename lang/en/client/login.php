<?php

declare(strict_types=1);

return [
    'title' => 'Log in',
    'subtitle' => 'To connect with great new talent',
    'form' => [
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Email',
        ],
        'password' => [
            'label' => 'Password',
            'placeholder' => 'Password',
        ],
        'submit_button' =>  'Log in >',
        'links' => [
            'register_label' => "Don't have an account?",
            'register' => 'Create account',
            'forgot_password' => 'Forgot password?',
        ],
        'notification' => [
            'success' => 'You was successfully signed in.',
            'failed' => 'Something went wrong, please try again.',
        ],
    ],
    'message' => [
        'failed' => 'These credentials do not match our records.',
        'password' => 'The provided password is incorrect.',
        'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    ],
    'email_verification_request' => [
        'button' => 'Resend verification email',
        'success' => 'Email verification notification has been successfully sent.',
        'message' => 'Your email is not verified. Please verify. If you didn\'t receive email, please press',
        'error' => 'Your email is not verified.',
    ],
];
