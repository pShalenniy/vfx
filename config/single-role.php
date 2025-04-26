<?php

declare(strict_types=1);

use App\Models\User;

return [
    'models' => [
        'user' => User::class,
    ],

    'tables' => [
        'users' => 'users',
        'roles' => 'roles',
        'permissions' => 'permissions',

        // Pivot tables.
        'permission_role' => 'permission_role',
        'permission_user' => 'permission_user',
    ],

    // Delimiter for passing roles and permissions as string.
    'delimiter' => '|',

    'roles' => [
        'super-admin' => [
            'permissions' => [
                'candidate.list' => 'Show candidates list',
                'candidate.create' => 'Create candidate',
                'candidate.edit' => 'Edit candidate',
                'candidate.delete' => 'Delete candidate',
                'candidate.mark-starred' => 'Mark candidate as starred',

                'cms.block.list' => 'Show content data list',
                'cms.block.set' => 'Set content data',
                'cms.block.logo' => 'All actions with logo',
                'cms.block.social' => 'All actions with social',
                'cms.block.contacts' => 'All actions with contacts',
                'cms.block.our-partners.list' => 'Show our partners list',
                'cms.block.our-partners.create' => 'Create our partners',
                'cms.block.our-partners.edit' => 'Edit our partners',
                'cms.block.our-partners.delete' => 'Delete our partners',
                'cms.page.home.top.block.1.title' => 'Update title in top block #1',
                'cms.page.home.top.block.1.text' => 'Update text in top block #1',
                'cms.page.home.top.block.1.image' => 'Update image in top block #1',
                'cms.page.home.top.block.2.title' => 'Update title in top block #2',
                'cms.page.home.top.block.2.text' => 'Update text in top block #2',
                'cms.page.home.top.block.2.image' => 'Update image in top block #2',
                'cms.page.home.top.block.3.title' => 'Update title in top block #3',
                'cms.page.home.top.block.3.text' => 'Update text in top block #3',
                'cms.page.home.top.block.3.image' => 'Update image in top block #3',
                'cms.page.home.block.intro' => 'Update all data in block intro',
                'cms.page.home.block.video' => 'Update all data in video block',
                'cms.page.about-us' => 'Update all data in about us page',
                'cms.page.subscription.block.intro' => 'Update data in intro block',
                'cms.page.subscription.thank-you.block.text' => 'Update text in block',
                'cms.page.subscription.inactive.block.text' => 'Update text in block',
                'cms.page.privacy-policy.text' => 'Update privacy policy text',
                'cms.page.terms-and-conditions.text' => 'Update terms and conditions text',
                'cms.page.terms-and-conditions.disclaimer' => 'Update terms and conditions disclaimer',
                'cms.page.active-sessions.text' => 'Update text on active sessions page',
                'cms.page.contact-us.disclaimer' => 'Update disclaimer on contact us page',
                'cms.block.tinsel_town.app.store' => 'Update link to app store for Tinsel Town',
                'cms.block.tinsel_town.google.play' => 'Update link to google play for Tinsel Town',

                'email-template-setting.page' => 'Access to email template settings page',
                'email-template-setting.list' => 'Email template settings list',
                'email-template-setting.edit' => 'Edit email template setting',

                'skill.list' => 'Show skills list',
                'skill.search' => 'Search skills',
                'skill.page' => 'Access to skills page',
                'skill.create' => 'Create skill',
                'skill.edit' => 'Edit skill',
                'skill.delete' => 'Delete skill',

                'subscription.update.has-expired' => 'Ability to update has expired attribute',
                'subscription.renew' => 'Ability to renew subscription',

                'role.page' => 'Access to roles page',
                'role.create' => 'Create role',
                'role.edit' => 'Edit role',
                'role.delete' => 'Delete role',

                'timezone.page' => 'Access to timezones page',
                'timezone.create' => 'Create timezone type',
                'timezone.edit' => 'Edit timezone type',
                'timezone.delete' => 'Delete timezone',

                'user.page' => 'Access to users page',
                'user.create' => 'Create user',
                'user.edit' => 'Edit user',
                'user.delete' => 'Delete user',
            ],
        ],
    ],
];
