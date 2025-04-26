<?php

declare(strict_types=1);

return [
    'table' => [
        'columns' => [
            'picture' => 'Picture',
            'full_name' => 'Full name',
            'email' => 'Email',
            'skills' => 'Key skills',
            'company' => 'Company',
            'nationalities' => 'Nationalities',
            'alternative_citizenship_residencies' => 'Alternative citizenships/residencies',
            'current_job_roles' => 'Current job roles',
            'next_availability' => 'Next availability',
            'created_at' => 'Created at',
            'social_media' => 'Social media',
            'actions' => 'Actions',
        ],
        'empty' => 'There are no records',
    ],
    'action' => [
        'create' => [
            'button' => 'Create',
            'success' => 'Candidate was successfully created',
        ],
        'edit' => [
            'button' => 'Edit',
            'success' => 'Candidate was successfully updated',
        ],
        'delete' => [
            'button' => 'Delete',
            'success' => 'Candidate was successfully deleted',
            'failed' => 'Something went wrong, please try again.'
        ],
        'add_star_candidate' => 'Star candidate has been successfully added',
    ],
    'form' => [
        'groups' => [
            'personal' => 'Personal',
            'professional' => 'Professional',
            'contact_social' => 'Contact/Social',
            'remuneration' => 'Remuneration',
            'additional_notes' => 'Additional notes',
        ],
        'picture' => [
            'label' => 'Picture',
            'placeholder' => 'Choose a file or drop it here...',
            'drop_placeholder' => 'Drop file here...',
        ],
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email',
        'preferred_location' => 'Preferred location',
        'nationalities' => 'Nationalities',
        'skills' => 'Key skills',
        'want_learn_skills' => 'Key skills you want to learn',
        'want_work_with_tools' => 'Tools you want to work with',
        'preferred_work_environments' => 'Preferred work environments',
        'current_job_role' => [
            'label' => 'Current role',
            'placeholder' => 'Choose current role',
        ],
        'city' => 'Current city/town',
        'region' => 'Current region',
        'country' => 'Current country',
        'timezone' => 'Timezone',
        'company' => 'Company',
        'television_show' => 'Biggest show worked on',
        'alternative_citizenship_residencies' => 'Alternative citizenships/residencies',
        'budget_of_biggest_show' => 'VFX budget of the biggest show (USD)',
        'cell_phone_number' => 'Cell phone number',
        'portfolio_url' => 'Portfolio URL',
        'shortfilm_url' => 'Shortfilm URL',
        'gross_annual_salary' => 'Gross annual salary',
        'week_rate' => 'Week rate',
        'day_rate' => 'Day rate',
        'would_like_work_on' => 'What I would like to work on',
        'desired_job_role' => [
            'label' => 'Desired role',
            'placeholder' => 'Choose desired role',
        ],
        'next_promotion_job_role' => [
            'label' => 'Your next promotion',
            'placeholder' => 'Choose your next promotion',
        ],
        'preferred_sectors' => 'Preferred sectors',
        'travel_availability' => [
            'title' => 'Travel availability',
            'status' => 'Is available',
        ],
        'salary_rate_currency' => 'Salary rate currency',
        'vfx_notes' => 'vfx notes',
        'imdb_link' => 'IMDB URL',
        'linkedin_link' => 'Linkedin URL',
        'instagram_link' => 'Instagram handle',
        'twitter_link' => 'Twitter handle',
        'next_availability' => 'Next availability',
        'current_work' => 'Current work',
        'previous_work' => 'Previous work',
        'professional_interest' => 'Professional interest',
        'commercial_experience' => 'Commercial experience',
    ],
    'modal' => [
        'end_period' => 'End period',
        'start_period' => 'Start period',
        'key_skill_level' => [
            'text' => 'Please select the level for key skill',
        ],
    ],
    'filters' => [
        'search' => 'Search over all',
        'starred_candidates' => 'Star candidates only',
    ],
];
