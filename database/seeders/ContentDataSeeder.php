<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ContentData;
use Database\Seeders\Traits\UploadFileTrait;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Throwable;

use function file_exists;
use function file_get_contents;
use function in_array;

use const null;
use const true;

class ContentDataSeeder extends Seeder
{
    use UploadFileTrait;

    public function run(): void
    {
        $storageDisk = ContentData::STORAGE_DISK;

        foreach ($this->getContentData() as $data) {
            $fileFields = Config::get('cms.file-fields');

            $fileFields = $fileFields['image'];

            if (in_array($data['key'], $fileFields, true)) {
                $filename = $data['name'];
                $file = new UploadedFile($data['path'], $filename);

                try {
                    $uploadFile = $this->storeUploadedFile($file, 'content-data', $storageDisk);

                    $this->saveContentData(['key' => $data['key'] ?? null, 'value' => $uploadFile]);
                } catch (Throwable $e) {
                    if (isset($uploadFile) && file_exists($uploadFile)) {
                        Storage::disk($storageDisk)->delete($uploadFile);
                    }
                }
            } else {
                $this->saveContentData($data);
            }
        }
    }

    protected function getContentData(): array
    {
        return [
            [
                'key' => ContentData::KEY_BLOCK_LOGO,
                'name' => 'logo.png',
                'path' => 'database/seeders/data/images/logo.png',
            ],
            [
                'key' => ContentData::KEY_BLOCK_SOCIAL_INSTAGRAM,
                'value' => 'https://www.instagram.com/vfx',
            ],
            [
                'key' => ContentData::KEY_BLOCK_SOCIAL_LINKEDIN,
                'value' => 'https://www.linkedin.com/company/vfx',
            ],
            [
                'key' => ContentData::KEY_BLOCK_SOCIAL_TWITTER,
                'value' => 'https://twitter.com/vfx',
            ],
            [
                'key' => ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_1,
                'value' => '653 Canyon Rd',
            ],
            [
                'key' => ContentData::KEY_BLOCK_CONTACT_ADDRESS_LINE_2,
                'value' => 'New Mexico, United States',
            ],
            [
                'key' => ContentData::KEY_BLOCK_CONTACT_EMAIL,
                'value' => 'info@test.com',
            ],
            [
                'key' => ContentData::KEY_BLOCK_CONTACT_PHONE,
                'value' => '080 000 000',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_TITLE,
                'value' => 'Are you hiring in the VFX or Virtual Production space?',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_TEXT,
                'value' => 'Sign up for VFX, our subscription service which showcases top, available candidates in Europe and the Americas across all departments',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE,
                'name' => 'girl.jpg',
                'path' => 'database/seeders/data/images/hero/girl.jpg',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_TITLE,
                'value' => 'Already signed up for VFX and ready to hire?',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_TEXT,
                'value' => 'Search now for candidates globally which match your needs/interests and reach out to them directly',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE,
                'name' => 'boy.jpg',
                'path' => 'database/seeders/data/images/hero/boy.jpg',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_TITLE,
                'value' => 'Are you a professional in the VFX, Animation or Virtual Production space looking to network?',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_TEXT,
                'value' => 'Check out our awesome, free professional networking mobile app specifically for creative professionals across the world',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE,
                'name' => 'star.svg',
                'path' => 'database/seeders/data/images/icons/star.svg',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_BLOCK_INTRO_TEXT,
                'value' => 'Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text.',
            ],
            [
                'key' => ContentData::KEY_PAGE_HOME_BLOCK_INTRO_EXPANDED_TEXT,
                'value' => 'Sign up and you will be able to search/filter candidates in Europe and the Americas which match your needs/interests and communicate with them directly. Our team vets each candidate carefully and regularly to ensure their availability and information is up to date. All candidates must have at least three years commercial experience to be eligible for VFX and the platform showcases profiles up to and including senior management level.',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_TITLE,
                'value' => 'About us',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_TEXT,
                'value' => 'is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_TITLE,
                'value' => 'The team',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_FULL_NAME,
                'value' => 'John Dou',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_POSITION,
                'value' => 'CEO',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE,
                'name' => 'john.jpg',
                'path' => 'database/seeders/data/images/about-us/john.jpg',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_TEXT,
                'value' => 't is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_EMAIL,
                'value' => 'john@vfx-hire.com',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_LINK_TITLE,
                'value' => 'Contact John >',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_FULL_NAME,
                'value' => 'Allen Black',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_POSITION,
                'value' => 'CEO',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE,
                'name' => 'allen.jpg',
                'path' => 'database/seeders/data/images/about-us/allen.png',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_TEXT,
                'value' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_EMAIL,
                'value' => 'allen@gmail.com',
            ],
            [
                'key' => ContentData::KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_LINK_TITLE,
                'value' => 'Contact Allen >',
            ],
            [
                'key' => ContentData::KEY_PAGE_SUBSCRIPTION_BLOCK_INTRO,
                'value' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam',
            ],
            [
                'key' => ContentData::KEY_PAGE_SUBSCRIPTION_THANK_YOU_BLOCK_TEXT,
                'value' => 'Thank you for your interest in VFX. We will get back to you via email asap to book in a demo and answer any questions you might have.',
            ],
            [
                'key' => ContentData::KEY_PAGE_SUBSCRIPTION_INACTIVE_BLOCK_TEXT,
                'value' => 'Dear user, you have been redirected to this page because your subscription is not active. Please wait until the admin activates your subscription. If you have previously paused your subscription, please contact your administrator to resume it.',
            ],
            [
                'key' => ContentData::KEY_PAGE_PRIVACY_POLICY_TEXT,
                'value' => file_get_contents(
                    $this->container->databasePath('seeders/data/pages/privacy-policy.html'),
                ),
            ],
            [
                'key' => ContentData::KEY_PAGE_TERMS_AND_CONDITIONS_TEXT,
                'value' => file_get_contents(
                    $this->container->databasePath('seeders/data/pages/terms-and-conditions.html'),
                ),
            ],
            [
                'key' => ContentData::KEY_PAGE_TERMS_AND_CONDITIONS_DISCLAIMER,
                'value' => file_get_contents(
                    $this->container->databasePath('seeders/data/pages/disclaimer.html'),
                ),
            ],
            [
                'key' => ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER,
                'value' => 'or fill out this form to inquire about connecting, if you have a question, or simply to say hello.',
            ],
            [
                'key' => ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL_TEXT,
                'value' => 'Email',
            ],
            [
                'key' => ContentData::KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL,
                'value' => 'info@vfx.pro',
            ],
            [
                'key' => ContentData::KEY_PAGE_ACTIVE_SESSIONS_TEXT,
                'value' => 'Dear user, you have been redirected to this page because you exceeded the login limit. Our site has a limit of no more than two active sessions. To continue using the site, please remove one of the active sessions from the list below.',
            ],
        ];
    }

    protected function saveContentData(array $data): void
    {
        ContentData::query()->updateOrCreate(
            Arr::only($data, 'key'),
            Arr::only($data, 'value'),
        );
    }
}
