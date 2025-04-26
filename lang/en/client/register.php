<?php

declare(strict_types=1);

return [
    'title' => 'Sign up',
    'subtitle' => 'To become part of the great new talent hub',
    'form' => [
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' =>  'Email',
        'company' => [
            'label' => 'Company',
            'placeholder' => 'Type your company',
        ],
        'country' => 'Country',
        'region' => 'Region',
        'city' => 'City',
        'has_signatory' => [
            'title' => 'Has signatory',
            'text' => 'Do you have signatory authority to engage in a partnership with vfx?',
        ],
        'job_title' => 'Job title',
        'preferred_job_roles' => 'Requirements in terms of roles/jobs',
        'phone_number' => 'Phone number',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'submit_button' =>  'Sign up >',
        'link' => [
            'label' => 'Already have an account?',
            'login' => 'Log in',
        ],
    ],
    'notification' => [
        'sign_up' => [
            'success' => 'You was successfully signed up.',
            'failed' => 'Something went wrong, please try again.',
            'message' => 'An account with this email address is already registered, try to log in',
        ],
    ],
];
