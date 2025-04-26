<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\EmailTemplateSetting;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class EmailTemplateSettingTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanNotSeeList(): void
    {
        EmailTemplateSetting::factory()->create();

        $this->signInAsUser()->get(URL::route('admin.email-template-setting.list'))->assertStatus(403);

        $this->assertDatabaseCount('email_template_settings', 1);
    }

    public function testUserCanSeeList(): void
    {
        EmailTemplateSetting::factory()->create();

        $this->signInAsAdmin()
            ->getJson(URL::route('admin.email-template-setting.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'key',
                        'subject',
                        'body',
                        'emails',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('email_template_settings', 1);
    }

    public function testUserCanNotUpdate(): void
    {
        $emailTemplateSetting = EmailTemplateSetting::factory()->create();

        $this->signInAsUser()
            ->patch(
                URL::route('admin.email-template-setting.update', $emailTemplateSetting),
                $this->getEmailTemplateSettingData(),
            )
            ->assertStatus(403);

        $this->assertDatabaseCount('email_template_settings', 1);
    }

    public function testUserCanNotUpdateWithInvalidEmails(): void
    {
        $emailTemplateSetting = EmailTemplateSetting::factory()->create();

        $emailTemplateSettingData = [
            'key' => EmailTemplateSetting::KEY_CLIENT_CONTACT_US,
            'subject' => 'Contact us notification',
            'body' => 'Thank you for your request',
            'emails' => 11,
        ];

        $this->signInAsAdmin()
            ->patchJson(
                URL::route('admin.email-template-setting.update', $emailTemplateSetting),
                $emailTemplateSettingData,
            )
            ->assertStatus(422);

        $this->assertDatabaseCount('email_template_settings', 1);
    }

    public function testUserCanUpdate(): void
    {
        $emailTemplateSetting = EmailTemplateSetting::factory()->create();

        $this->signInAsAdmin()
            ->patch(
                URL::route('admin.email-template-setting.update', $emailTemplateSetting),
                $this->getEmailTemplateSettingData(),
            )
            ->assertStatus(201);

        $this->assertDatabaseCount('email_template_settings', 1);
    }

    protected function getEmailTemplateSettingData(): array
    {
        return [
            'key' => EmailTemplateSetting::KEY_CLIENT_CONTACT_US,
            'subject' => 'Contact us notification',
            'body' => 'Thank you for your request',
            'emails' => ['test@example.com', 'admin@example.com'],
        ];
    }
}
