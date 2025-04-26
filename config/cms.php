<?php

declare(strict_types=1);

use App\Models\ContentData;

return [
    'validation' => [
        ContentData::KEY_BLOCK_LOGO => ['required'],
        ContentData::KEY_BLOCK_SOCIAL_INSTAGRAM => [
            'nullable',
            'string',
            'regex:~^(https?://)?(www\.)?instagram\.com/~',
        ],
        ContentData::KEY_BLOCK_SOCIAL_LINKEDIN => [
            'nullable',
            'string',
            'regex:~^(https?://)?(www\.)?linkedin\.com/~',
        ],
        ContentData::KEY_BLOCK_SOCIAL_TWITTER => [
            'nullable',
            'string',
            'regex:~^(https?://)?(www\.)?twitter\.com/~',
        ],
        ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_1 => ['required', 'string'],
        ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_2 => ['required', 'string'],
        ContentData::KEY_BLOCK_CONTACT_EMAIL => ['string', 'max:255', 'email'],
        ContentData::KEY_BLOCK_CONTACT_PHONE => ['required'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_TITLE => ['required', 'string'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE => ['required'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_TITLE => ['required', 'string'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE => ['required'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_TITLE => ['required', 'string'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE => ['required'],
        ContentData::KEY_PAGE_HOME_BLOCK_INTRO_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_HOME_BLOCK_INTRO_EXPANDED_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_TITLE => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_FULL_NAME => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_POSITION => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE => ['required'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_EMAIL => ['required', 'max:255', 'email'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_LINK_TITLE => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_FULL_NAME => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_POSITION => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE => ['required'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_EMAIL => ['required', 'max:255', 'email'],
        ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_LINK_TITLE => ['required', 'string'],
        ContentData::KEY_PAGE_SUBSCRIPTION_BLOCK_INTRO => ['required', 'string'],
        ContentData::KEY_PAGE_SUBSCRIPTION_THANK_YOU_BLOCK_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_SUBSCRIPTION_INACTIVE_BLOCK_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_PRIVACY_POLICY_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_TERMS_AND_CONDITIONS_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_ACTIVE_SESSIONS_TEXT => ['required', 'string'],
        ContentData::KEY_PAGE_TERMS_AND_CONDITIONS_DISCLAIMER => ['required', 'string'],
        ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER => ['required', 'string'],
        ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL => ['required', 'max:255'],
        ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL_TEXT => ['required', 'string'],
        ContentData::KEY_BLOCK_TINSEL_TOWN_APP_STORE => [
            'nullable',
            'string',
            'regex:~^(https?://)?(www\.)?apps\.apple\.com/~',
        ],
        ContentData::KEY_BLOCK_TINSEL_TOWN_GOOGLE_PLAY => [
            'nullable',
            'string',
            'regex:~^(https?://)?(www\.)?play\.google\.com/~',
        ],
    ],
    'file-fields' => [
        'image' => [
            ContentData::KEY_BLOCK_LOGO,
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE,
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE,
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE,
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE,
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE,
        ],
    ],
    'files' => [
        'extensions' => [
            'image' => ['jpeg', 'jpg', 'png', 'bmp', 'gif'],
        ],
        'sizes' => [
            'image' => 5120,
        ],
    ],
];
