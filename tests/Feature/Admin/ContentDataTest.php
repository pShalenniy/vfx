<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\ContentData;
use Database\Seeders\ContentDataSeeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

use function str_replace;

class ContentDataTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSet(): void
    {
        $this->seed(ContentDataSeeder::class);

        $contentData = [
            ContentData::KEY_BLOCK_LOGO => Storage::disk('public')->url('content-data/noimage.png'),
            ContentData::KEY_BLOCK_SOCIAL_INSTAGRAM => 'https://www.instagram.com/',
            ContentData::KEY_BLOCK_SOCIAL_LINKEDIN => 'https://www.linkedin.com/',
            ContentData::KEY_BLOCK_SOCIAL_TWITTER => 'https://www.twitter.com/',
            ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_1 => '2a Castle Street, Tunbridge',
            ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_2 => 'Wells, England, TN1 1XJ',
            ContentData::KEY_BLOCK_CONTACT_EMAIL => 'test@vfx.com',
            ContentData::KEY_BLOCK_CONTACT_PHONE => '020 8375 1273',
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_TITLE => 'VFX networking app',
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_TEXT => 'VFX networking app',
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE => Storage::disk('public')->url('content-data/noimage.png'),
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_TITLE => 'VFX networking app',
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_TEXT => 'Connect with great new talent',
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE => Storage::disk('public')->url('content-data/noimage.png'),
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_TITLE => 'VFX networking app',
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_TEXT => 'Connect with great new talent',
            ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE => Storage::disk('public')->url('content-data/noimage.png'),
            ContentData::KEY_PAGE_HOME_BLOCK_INTRO_TEXT => 'Check our story and learn what we can do for you',
            ContentData::KEY_PAGE_HOME_BLOCK_INTRO_EXPANDED_TEXT => 'Check our story and learn what we can do for you',
            ContentData::KEY_PAGE_ABOUT_US_TITLE => 'About us',
            ContentData::KEY_PAGE_ABOUT_US_TEXT => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_FULL_NAME => 'John Dou',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_POSITION => 'CEO',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE => Storage::disk('public')->url('content-data/noimage.png'),
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_TEXT => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_EMAIL => 'test@gmail.com',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_LINK_TITLE => 'Contact John >',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_FULL_NAME => 'Allen Black',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_POSITION => 'CEO',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE => Storage::disk('public')->url('content-data/noimage.png'),
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_TEXT => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_EMAIL => 'test@gmail.com',
            ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_LINK_TITLE => 'Contact Allen >',
            ContentData::KEY_PAGE_SUBSCRIPTION_BLOCK_INTRO => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_SUBSCRIPTION_THANK_YOU_BLOCK_TEXT => 'Thank you, we come back to you as soon as possible.',
            ContentData::KEY_PAGE_SUBSCRIPTION_INACTIVE_BLOCK_TEXT => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_PRIVACY_POLICY_TEXT => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_TERMS_AND_CONDITIONS_TEXT => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_TERMS_AND_CONDITIONS_DISCLAIMER => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL => 'test@gmail.com',
            ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL_TEXT => 'email',
            ContentData::KEY_PAGE_ACTIVE_SESSIONS_TEXT => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
        ];

        foreach ($contentData as $key => $value) {
            $contentData[str_replace('.', '_', $key)] = $value;
        }

        $this->signInAsAdmin()
            ->patch(URL::route('admin.content-data.set'), $contentData)
            ->assertStatus(201);
    }
}
