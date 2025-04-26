<?php

declare(strict_types=1);

use App\Models\Candidate;
use App\Models\Pivot\CandidateSkill;

return [
    'table' => [
        'empty' => 'There are no records',
    ],
    'exception' => [
        'cant-upload-file' => "Can't upload file :filename",
        'database' => "Can't store in database",
        'parser' => [
            'invalid_name' => 'Provided parser key :parser_name is invalid',
        ],
        'elasticsearch' => [
            'invalid_argument' => 'Method for bulk is invalid',
            'invalid_method' => 'Method must be "index", "update" or "delete"',
            'invalid_model' => 'Provide $model',
            'invalid_index' => 'Provide $index',
            'filter_runtime' => 'Filter must implement ElasticsearchFilter',
        ],
    ],
    'our_partners' => 'Our partners',
    'tinsel_town_google_play' => 'Google play',
    'tinsel_town_app_store' => 'App store',
    'sign_up_link' => 'Sign up',
    'search_link' => 'Search',
    'header' => [
        'menu' => [
            'admin_part' => 'Admin part',
            'home' => 'Home',
            'search' => 'Search',
            'about_us' => 'About us',
            'contact_us' => 'Contact',
            'candidate_login' => 'Candidate login',
            'account_settings' => 'Account settings',
            'logout' => 'Logout',
            'login' => 'Login',
        ],
    ],
    'contact' => 'Contact',
    'follow' => 'Follow',
    'button' => [
        'register' => 'Register',
    ],
    'link' => [
        'terms_and_conditions' => 'Terms & Conditions',
        'privacy_policy' => 'Privacy policy',
    ],
    'showing_to_of_total_entries' => 'Showing :from to :to of :total entries',
    'vue_select' => [
        'no_results_found' => 'No results found for',
        'start_typing' => 'Start typing to search',
        'not_selected' => '-- Not Selected --',
    ],
    'permission_denied' => 'Permission denied',
    'toggle_sidebar' => 'Toggle sidebar',
    'confirmation' => [
        'confirm' => [
            'buttons' => [
                'confirm' => 'Yes',
                'deny' => 'No',
            ],
        ],
        'delete' => [
            'are_you_sure' => 'Are you sure?',
            'you_wont_revert' => "You won't be able to revert this!",
            'yes_delete' => 'Yes, delete it!',
            'yes_update' => 'Yes, update it!',
        ],
        'delete_candidate_from_shortlist' => [
            'title' => 'Are you sure you want to delete the selected candidate?',
            'confirm_button' => 'Delete',
        ],
        'delete_shortlist' => [
            'title' => 'Are you sure you want to delete the selected shortlist?',
            'confirm_button' => 'Delete',
        ],
        'cancel' => 'Cancel',
    ],
    'show_more' => 'Show more',
    'show_less' => 'Show less',
    'see_all' => 'See all',
    'see_less' => 'See less',
    'constants' => [
        'candidate' => [
            'budget_of_biggest_show' => [
                Candidate::BUDGET_OF_BIGGEST_SHOW_0_25M => '$0-25 million',
                Candidate::BUDGET_OF_BIGGEST_SHOW_25M_50M => '$25-50 million',
                Candidate::BUDGET_OF_BIGGEST_SHOW_50M_100M => '$50-100 million',
                Candidate::BUDGET_OF_BIGGEST_SHOW_100M_150M => '$100-150 million',
                Candidate::BUDGET_OF_BIGGEST_SHOW_GT150M => '$150 million +',
            ],
            'salary_rate_currency' => [
                Candidate::SALARY_RATE_CURRENCY_USD => 'USD',
                Candidate::SALARY_RATE_CURRENCY_CAD => 'CAD',
                Candidate::SALARY_RATE_CURRENCY_EURO => 'EURO',
                Candidate::SALARY_RATE_CURRENCY_GBP => 'GBP',
                Candidate::SALARY_RATE_CURRENCY_FRANC => 'FRANC',
                Candidate::SALARY_RATE_CURRENCY_ROUBLE => 'ROUBLE',
                Candidate::SALARY_RATE_CURRENCY_KRONE => 'KRONE',
            ],
        ],
        'candidate_skill' => [
            'level' => [
                CandidateSkill::LEVEL_ADVANCED => '"Advanced level"',
                CandidateSkill::LEVEL_INTERMEDIATE => '"Intermediate level"',
            ],
        ],
    ],
    'terms_and_conditions' => 'See Terms & Conditions',
    'disclaimer' => 'Disclaimer',
    'candidate' => [
        'skill_circles' => [
            'technical' => 'Technical',
            'creative' => 'Creative',
            'production' => 'Production',
        ],
    ],
    'user_company' => [
        'title' => 'Company',
        'edit' => 'Edit',
        'save' => 'Save',
        'name' => 'Name',
        'logo' => [
            'label' => 'Logo',
            'placeholder' => 'Choose a file or drop it here...',
            'drop_placeholder' => 'Drop file here...',
        ],
        'url' => 'URL',
        'create' => 'Create a new one',
        'update' => 'Update',
        'select_from_list' => 'Select from list',
    ],
    'update' => 'Update',
    'cancel' => 'Cancel',
    'our_story' => 'Our story',
];
