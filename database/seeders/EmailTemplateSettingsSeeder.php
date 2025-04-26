<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\EmailTemplateSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class EmailTemplateSettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getEmailTemplateSettingData() as $data) {
            EmailTemplateSetting::query()->updateOrCreate(
                Arr::only($data, 'key'),
                Arr::except($data, 'key'),
            );
        }
    }

    protected function getEmailTemplateSettingData(): array
    {
        return [
            [
                'key' => EmailTemplateSetting::KEY_ADMIN_USER_CREATED,
                'subject' => 'Register notification',
                'body' => '<p>Hello.</p><p>You received this notification because you have been registered on the site.</p><p>Your login for authentication: [login].</p><p>Your password for authentication: [password].</p><p><a href="[url]">Go to the login page.</a></p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_ADMIN_USER_DELETED,
                'subject' => 'Delete notification',
                'body' => '<p>Hello.</p><p>Dear user, we would like to inform you that your page on the resource has been deleted.</p><p><p>If we have made a mistake, or you would like to rejoin VFX at any point, or you have any questions regarding your account please reply to this message or email foo@gmail.com, bar@gmail.com and we\'ll get back to you asap.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_ADMIN_CANDIDATE_CREATED,
                'subject' => 'Register notification',
                'body' => '<p>Hello.</p><p>You received this notification because you have been registered on the site.</p><p>Your login for authentication: [login]</p><p>Your password for authentication: [password]</p><p><a href="[url]">Go to the login page.</a></p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_ADMIN_SUBSCRIPTION_REQUEST_PAUSE,
                'subject' => 'Request subscription pause notification',
                'body' => '<p>Hello.</p><p>The user sent a request to pause a subscription on the site. Please handle the request.</p><p><b>Subscription information</b></p><p>Seats: [subscription:seats]</p><p>Departments: [subscription:departments]</p><p><b>User information</b></p><p>First name: [user:first_name]</p><p>Last name: [user:last_name]</p><p>Email: [user:email]</p><p>Phone number: [user:phone_number]</p><p>Company: [user:company]</p><p>Job title: [user:job_title]</p><p>Country: [user:country]</p><p>Region: [user:region]</p><p>City: [user:city]</p><p>Has signatory: [user:has_signatory]</p><p>Preferred job roles: [user:preferred_job_roles]</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_FIRST_PAUSED_PERIOD,
                'subject' => 'VFX subscription renewal time',
                'body' => '<p>Hello. The users current subscription period will be coming to an end. VFX will automatically extend a subscription by a subscription period.</p><hr>[loop:users]<p><p><b>Subscription information</b></p><p>Period: [subscription:period] month</p><p>Seats: [subscription:seats]</p><p>End date: [subscription:end_date]</p><p>Departments: [subscription:departments]</p><p><b>User information</b></p><p>First name: [user:first_name]</p><p>Last name: [user:last_name]</p><p>Email: [user:email]</p><p>Phone number: [user:phone_number]</p><p>Company: [user:company]</p><p>Job title: [user:job_title]</p><p>Country: [user:country]</p><p>Region: [user:region]</p><p>City: [user:city]</p><p>Has signatory: [user:has_signatory]</p><p>Preferred job roles: [user:preferred_job_roles]</p><hr>[/loop:users]<p><p>The user\'s subscription can be renewed after [subscription:days_for_renewal] days.</p>Please handle the request.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_REQUEST_CHANGE,
                'subject' => 'Request subscription change notification',
                'body' => '<p>Hello.</p><p>The user [user:first_name] [user:last_name], email: [user:email], phone number: [user:phone_number].</p><p><b>Subscription information</b></p><p>Seats: [subscription:seats]</p><p>Departments: [subscription:departments]</p><p>Has sent a request to change the subscription. Please handle the request.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_CLIENT_SUBSCRIPTION_REQUEST_CHANGE,
                'subject' => 'Request subscription change notification',
                'body' => '<p>Hello.</p><p>The request has been accepted for processing, please wait for the manager to contact you.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_CONTACT_US,
                'subject' => 'Created new contact us request',
                'body' => '<p>Hello.</p><p>Please process contact us request</p><p>First name: [user:first_name]</p><p>Last name: [user:last_name]</p><p>Email: [user:email]</p><p>Telephone number: [user:telephone_number]</p><p>Enquiry: [enquiry]</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_FORGOT_PASSWORD,
                'subject' => 'Reset password notification',
                'body' => '<p>Hello.</p><p>You received this email because we received a password change request from your account.</p><p><a href="[link]">Reset</a></p><p>If you did not request a password reset, no further action is required.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_PROCESS_REQUEST_PAUSE,
                'subject' => 'Request subscription pause notification',
                'body' => '<p>Hello.</p><p>The request has been accepted for processing, please wait for the subscription suspension notification.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_REGISTRATION_COMPLETED,
                'subject' => 'Register notification',
                'body' => '<p>Hello.</p><p>Thank you for submitting your details.</p><p>We are verifying your details and will be touch when your account has been approved.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_RESET_PASSWORD,
                'subject' => 'Reset password notification',
                'body' => '<p>Hello.</p><p>You received this email because you changed your password.</p><p><a href="[link]">Return to login</a></p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_CREATED,
                'subject' => 'Subscription notification',
                'body' => '<p>Hello.</p><p>The user sent a request to receive a subscription on the site. Please handle the request.</p><p><b>Subscription information</b></p><p>Seats: [subscription:seats]</p><p>Departments: [subscription:departments]</p><p><b>User information</b></p><p>First name: [user:first_name]</p><p>Last name: [user:last_name]</p><p>Email: [user:email]</p><p>Phone number: [user:phone_number]</p><p>Company: [user:company]</p><p>Job title: [user:job_title]</p><p>Country: [user:country]</p><p>Region: [user:region]</p><p>City: [user:city]</p><p>Has signatory: [user:has_signatory]</p><p>Preferred job roles: [user:preferred_job_roles]</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_CANCELLED,
                'subject' => 'VFX subscription canceled',
                'body' => '<p>Dear [user:first_name], we wanted to let you know that on the [subscription:end_date], your VFX subscription will be terminated.</p><p>If we have made a mistake, or you would like to rejoin VFX at any point, or you have any questions regarding your subscription please reply to this message or email foo@gmail.com, bar@gmail.com and we\'ll get back to you asap. Please be aware that you will need to reapply to rejoin VFX should you wish to subscribe with our service again.</p><p>We are feel privileged that you chose VFX and we hope the platform has provided value to your talent sourcing efforts.</p><p>Kind regards,</p><p>Allen Black, John Dou</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_RENEW_FIRST_PAUSED_PERIOD,
                'subject' => 'VFX subscription renewal time',
                'body' => '<p>Dear [user:first_name], we wanted to let you know that on the [subscription:end_date], your current subscription period will be coming to an end. On the [subscription:end_date], VFX will automatically extend your subscription by a period of [subscription:period] month.</p><p>If you wish to cancel, pause or make a change to your subscription please respond to this message, or email foo@gmail.com, bar@gmail.com with your intentions.</p><p>We are feel privileged that you have chosen VFX and we hope the platform has and will continue to provide value to your talent sourcing efforts.</p><p>Kind regards,</p><p>Allen Black, John Dou</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_PAUSED,
                'subject' => 'VFX subscription renewal time',
                'body' => '<p>Hello. The users current subscription period will be coming to an end. VFX will automatically extend a subscription by subscription period.</p><hr>[loop:users]<p><b>Subscription information</b></p><p>Period: [subscription:period] month</p><p>Seats: [subscription:seats]</p><p>End date: [subscription:end_date]</p><p>Departments: [subscription:departments]</p><p><b>User information</b></p><p>First name: [user:first_name]</p><p>Last name: [user:last_name]</p><p>Email: [user:email]</p><p>Phone number: [user:phone_number]</p><p>Company: [user:company]</p><p>Job title [user:job_title]</p><p>Country: [user:country]</p><p>Region: [user:region]</p><p>City: [user:city]</p><p>Has signatory: [user:has_signatory]</p><p>Preferred job roles: [user:preferred_job_roles]</p><hr>[/loop:users]',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_RENEW_PAUSED,
                'subject' => 'VFX subscription renewal time',
                'body' => '<p>Dear [user:first_name], we wanted to let you know that on the [subscription:end_date], VFX will automatically extend your subscription by a period of [subscription:period] month.</p><p>If you wish to cancel, pause your subscription again for a period of 3 months, or make a change to your subscription, please respond to this message, or email foo@gmail.com, bar@gmail.com with your intentions.</p><p>We are feel privileged that you have chosen VFX and we hope the platform has and will continue to provide value to your talent sourcing efforts.</p><p>Kind regards,</p><p>Allen Black, John Dou</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_RENEW_LAST_PAUSED_PERIOD,
                'subject' => 'VFX subscription renewal time',
                'body' => '<p>Dear [user:first_name], we wanted to let you know that on the [subscription:end_date], VFX will automatically extend your subscription by a period of [subscription:period] month.</p><p>You have now used your maximum allowance of pauses. If you wish to cancel or make a change to your subscription please respond to this message, or email foo@gmail.com, bar@gmail.com with your intentions.</p><p>We are feel privileged that you have chosen VFX and we hope the platform has and will continue to provide value to your talent sourcing efforts.</p><p>Kind regards,</p><p>Allen Black, John Dou</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_ADMIN_SUBSCRIPTION_RENEW_LAST_PAUSED_PERIOD,
                'subject' => 'VFX subscription renewal time',
                'body' => '<p>Hello. The users current subscription period will be coming to an end. VFX will automatically extend a subscription by a subscription period.</p><p>The users have been used the maximum allowance of pauses.</p><hr>[loop:users]<p><b>Subscription information</b></p><p>Period: [subscription:period] month</p><p>Seats: [subscription:seats]</p><p>End date: [subscription:end_date]</p><p>Departments: [subscription:departments]</p><p><b>User information</b></p><p>First name: [user:first_name]</p><p>Last name: [user:last_name]</p><p>Email: [user:email]</p><p>Phone number: [user:phone_number]</p><p>Company: [user:company]</p><p>Job title: [user:job_title]</p><p>Country: [user:country]</p><p>Region: [user:region]</p><p>City: [user:city]</p><p>Has signatory: [user:has_signatory]</p><p>Preferred job roles: [user:preferred_job_roles]</p><hr>[/loop:users]<p>The user\'s subscription can be renewed after [subscription:days_for_renewal] days.</p><p>Please handle the request.</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_SUBSCRIPTION_REQUEST_PAUSE,
                'subject' => 'VFX subscription paused',
                'body' => '<p>Dear [user:first_name], we wanted to let you know that on the [subscription:end_date], your VFX subscription will be paused for a period of 3 months until the [subscription:pause_end_date]. On the [subscription:pause_end_date], VFX will automatically extend your subscription by a period of [subscription:period] month.</p><p>If you have any questions regarding your subscription please email foo@gmail.com, bar@gmail.com and we\'ll get back to you asap.</p><p>We are feel privileged that you have chosen VFX and we hope the platform has and will continue to provide value to your talent-sourcing efforts.<p><p>Kind regards,</p><p>Allen Black, John Dou</p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
            [
                'key' => EmailTemplateSetting::KEY_CLIENT_VERIFY_EMAIL,
                'subject' => 'Verify your notification email',
                'body' => '<p>Thank you for singing up with vfx</p><p>Please verify your email address to continue singing up.</p><p><a href="[url]">Complete verification</a></p>',
                'emails' => ['foo@gmail.com', 'bar@gmail.com'],
            ],
        ];
    }
}
