<?php

declare(strict_types=1);

return [
    'search' => 'Search over all',
    'table' => [
        'columns' => [
            'id' => 'ID',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'company' => 'Company',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'has_signatory' => [
                'title' => 'Has signatory',
                'yes' => 'Yes',
                'no' => 'No',
            ],
            'job_title' => 'Job title',
            'preferred_job_roles' => 'Requirements in terms of roles/jobs',
            'phone_number' => 'Phone number',
            'role_id' => 'Role',
            'subscription' => [
                'departments' => 'Departments',
                'status' => 'Status',
                'period' => 'Period',
                'starts_at' => 'Start date',
                'ends_at' => 'End date',
                'contract_signed' => [
                    'title' => 'Contract signed',
                    'yes' => 'Yes',
                    'no' => 'No',
                ]
            ],
            'actions' => 'Actions',
        ],
        'empty' => 'There are no records',
    ],
    'action' => [
        'create' => [
            'button' => 'Create',
            'success' => 'User has been successfully created',
        ],
        'edit' => [
            'button' => 'Edit',
            'success' => 'User has been successfully updated',
        ],
        'delete' => [
            'button' => 'Delete',
            'success' => 'User has been successfully deleted',
            'failed' => 'Something went wrong, please try again.',
            'question' => [
                'title' => 'User delete notification',
                'text' => 'Do you want to notify user about deleting account?',
            ],
        ],
        'subscription' => [
            'approve' => [
                'button' => 'Approve subscription',
                'success' => 'Subscription has been successfully approved',
            ],
            'renew' => [
                'button' => 'Renew subscription',
                'success' => 'Subscription has been successfully renewed',
            ],
        ],
    ],
    'form' => [
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email',
        'company' => 'Company',
        'country' => 'Country',
        'region' => 'Region',
        'city' => 'City',
        'has_signatory' => [
            'title' => 'Has signatory',
            'text' => 'Does the user has signatory authority to engage in a partnership with vfx?',
        ],
        'job_title' => 'Job title',
        'preferred_job_roles' => 'Requirements in terms of roles/jobs',
        'phone_number' => 'Phone number',
        'role_id' => 'Role',
        'password' => 'Password',
        'password_confirmation' => 'Password confirmation',
        'role' => 'Role',
        'notify_user' => 'Notify created user',
        'is_verified' => 'Verified',
        'subscription' => [
            'departments' => 'Departments',
            'status' => 'Status',
            'seats' => 'Seats',
            'period' => 'Period',
            'starts_at' => 'Start date',
            'ends_at' => 'End date',
            'contract_signed' => 'Contract signed',
            'title' => 'Subscription',
            'status_changes_confirmation' => [
                'title' => 'Subscription status changes',
                'questions' => [
                    'active' => [
                        'question_1' => 'Has the user completed a demo call?',
                        'question_2' => 'Has the user signed the vfx agreement?',
                    ],
                    'cancelled' => [
                        'question' => 'Are you sure you wish to cancel this user\'s subscription?',
                    ],
                    'paused' => [
                        'question' => 'Are you sure you wish to pause this user\'s subscription for 3 months?'
                    ],
                ],
            ],
        ],
    ],
    'subscription' => [
        'confirmation' => [
            'approve' => 'Subscription mark as normal',
            'renew' => 'Subscription renew',
            'question' => 'Please confirm that the user has paid for the subscription for the next period.',
        ],
    ],
    'filters' => [
        'title' => 'Filters',
        'reset' => 'Reset filters',
        'subscription' => [
            'status' => 'Status',
            'period' => 'Period',
            'starts_at' => 'Starts at',
            'ends_at' => 'Ends at',
            'contract_signed' => 'Contract signed',
            'has_expired' => 'Has expired',
        ],
        'options' => [
            'not_selected' => '-- Not selected --',
            'boolean_selection' => [
                'yes' => 'Yes',
                'no' => 'No',
            ],
        ],
    ],
    'field_history' => [
        'hide_history' => 'Hide history',
        'show_history' => 'Show history',
        'has_changed' => 'has been changed',
        'from' => 'from',
        'to' => 'to',
    ],
];
