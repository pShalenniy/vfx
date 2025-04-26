<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

use function in_array;

use const true;

class ContentData extends Model
{
    use HasFactory;

    public const CACHE_KEY_ALL = 'content_data_all';
    public const CACHE_KEY_ACTIVE_SESSIONS_PAGE = 'content_data_active_session_page';
    public const CACHE_KEY_ABOUT_US = 'content_data_about_us_page';
    public const CACHE_KEY_CONTACT = 'content_data_contact';
    public const CACHE_KEY_CONTACT_US = 'content_data_contact_us';
    public const CACHE_KEY_HOME_PAGE = 'content_data_home_page';
    public const CACHE_KEY_PRIVACY_POLICY_PAGE = 'content_data_privacy_policy_page';
    public const CACHE_KEY_SUBSCRIPTION_INACTIVE_PAGE = 'content_data_subscription_inactive_page';
    public const CACHE_KEY_SUBSCRIPTION_PAGE = 'content_data_subscription_page';
    public const CACHE_KEY_SUBSCRIPTION_THANK_YOU_PAGE = 'content_data_subscription_thank_you_page';
    public const CACHE_KEY_TERMS_AND_CONDITIONS_PAGE = 'content_data_terms_and_conditions_page';
    public const CACHE_KEY_LOGO = 'content_data_logo';
    public const CACHE_KEY_SOCIAL = 'content_data_social';

    public const STORAGE_DISK = 'public';

    public const KEY_BLOCK_LOGO = 'block.logo';
    public const KEY_BLOCK_SOCIAL_INSTAGRAM = 'block.social.instagram';
    public const KEY_BLOCK_SOCIAL_LINKEDIN = 'block.social.linkedin';
    public const KEY_BLOCK_SOCIAL_TWITTER = 'block.social.twitter';
    public const KEY_BLOCK_CONTACT_ADDRESS_LINE_1 = 'block.contact.address.line.1';
    public const KEY_BLOCK_CONTACT_ADDRESS_LINE_2 = 'block.contact.address.line.2';
    public const KEY_BLOCK_CONTACT_EMAIL = 'block.contact.email';
    public const KEY_BLOCK_CONTACT_PHONE = 'block.contact.phone';
    public const KEY_PAGE_HOME_TOP_BLOCK_1_TITLE = 'page.home.top.block.1.title';
    public const KEY_PAGE_HOME_TOP_BLOCK_1_TEXT = 'page.home.top.block.1.text';
    public const KEY_PAGE_HOME_TOP_BLOCK_1_IMAGE = 'page.home.top.block.1.image';
    public const KEY_PAGE_HOME_TOP_BLOCK_2_TITLE = 'page.home.top.block.2.title';
    public const KEY_PAGE_HOME_TOP_BLOCK_2_TEXT = 'page.home.top.block.2.text';
    public const KEY_PAGE_HOME_TOP_BLOCK_2_IMAGE = 'page.home.top.block.2.image';
    public const KEY_PAGE_HOME_TOP_BLOCK_3_TITLE = 'page.home.top.block.3.title';
    public const KEY_PAGE_HOME_TOP_BLOCK_3_TEXT = 'page.home.top.block.3.text';
    public const KEY_PAGE_HOME_TOP_BLOCK_3_IMAGE = 'page.home.top.block.3.image';
    public const KEY_PAGE_HOME_BLOCK_INTRO_TEXT = 'page.home.block.intro.text';
    public const KEY_PAGE_HOME_BLOCK_INTRO_EXPANDED_TEXT = 'page.home.block.intro.expanded.text';
    public const KEY_PAGE_ABOUT_US_TITLE = 'page.about-us.title';
    public const KEY_PAGE_ABOUT_US_TEXT = 'page.about-us.text';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_TITLE = 'page.about-us.block.the_team.title';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_FULL_NAME = 'page.about-us.block.the_team.john.full.name';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_POSITION = 'page.about-us.block.the_team.john.position';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_IMAGE = 'page.about-us.block.the_team.john.image';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_TEXT = 'page.about-us.block.the_team.john.text';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_EMAIL = 'page.about-us.block.the_team.john.email';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_JOHN_LINK_TITLE = 'page.about-us.block.the_team.john.link.title';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_FULL_NAME = 'page.about-us.block.the_team.sascha.full.name';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_POSITION = 'page.about-us.block.the_team.sascha.position';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_IMAGE = 'page.about-us.block.the_team.sascha.image';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_TEXT = 'page.about-us.block.the_team.sascha.text';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_EMAIL = 'page.about-us.block.the_team.sascha.email';
    public const KEY_PAGE_ABOUT_US_BLOCK_THE_TEAM_ALLEN_LINK_TITLE = 'page.about-us.block.the_team.sascha.link.title';
    public const KEY_PAGE_SUBSCRIPTION_BLOCK_INTRO = 'page.subscription.block.intro';
    public const KEY_PAGE_SUBSCRIPTION_THANK_YOU_BLOCK_TEXT = 'page.subscription.thank-you.block.text';
    public const KEY_PAGE_SUBSCRIPTION_INACTIVE_BLOCK_TEXT = 'page.subscription.inactive.block.text';
    public const KEY_PAGE_PRIVACY_POLICY_TEXT = 'page.privacy-policy.text';
    public const KEY_PAGE_TERMS_AND_CONDITIONS_TEXT = 'page.terms-and-conditions.text';
    public const KEY_PAGE_TERMS_AND_CONDITIONS_DISCLAIMER = 'page.terms-and-conditions.disclaimer';
    public const KEY_PAGE_ACTIVE_SESSIONS_TEXT = 'page.active-sessions.text';
    public const KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL = 'page.contact-us.disclaimer.email';
    public const KEY_PAGE_CONTACT_US_DISCLAIMER_EMAIL_TEXT = 'page.contact-us.disclaimer.email.text';
    public const KEY_PAGE_CONTACT_US_DISCLAIMER = 'page.contact-us.disclaimer';
    public const KEY_BLOCK_TINSEL_TOWN_APP_STORE = 'block.tinsel.town.app.store';
    public const KEY_BLOCK_TINSEL_TOWN_GOOGLE_PLAY = 'block.tinsel.town.google.play';

    protected $table = 'content_data';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $casts = [
        'key' => 'string',
    ];

    public function getValueAttribute(): mixed
    {
        $value = $this->attributes['value'];

        $fileFields = Config::get('cms.file-fields');

        if (in_array($this->attributes['key'], $fileFields['image'], true)) {
            return Storage::disk(self::STORAGE_DISK)->url($value);
        }

        return $value;
    }
}
