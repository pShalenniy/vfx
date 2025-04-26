<?php

declare(strict_types=1);

namespace App\Repositories\MySQL\Common;

use App\Models\ContentData;
use App\Models\OurPartner;
use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ContentDataRepository implements ContentDataRepositoryContract
{
    public function getContactData(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_CONTACT,
            'block.contact.%',
        );
    }

    public function getDataForAboutUsPage(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_ABOUT_US,
            'page.about-us.%',
        );
    }

    public function getDataForContactUsPage(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_CONTACT_US,
            'page.contact-us.%',
        );
    }

    public function getDataForActiveSessionsPage(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_ACTIVE_SESSIONS_PAGE,
            ContentData::KEY_PAGE_ACTIVE_SESSIONS_TEXT,
        );
    }

    public function getDataForHomePage(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_HOME_PAGE,
            'page.home.%',
        );
    }

    public function getDataForPrivacyPolicyPage(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_PRIVACY_POLICY_PAGE,
            'page.privacy-policy.text',
        );
    }

    public function getDataForTermsAndConditionsPage(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_TERMS_AND_CONDITIONS_PAGE,
            'page.terms-and-conditions.%',
        );
    }

    public function getLogoData(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_LOGO,
            'block.logo',
        );
    }

    public function getOurPartners(): Collection
    {
        return Cache::rememberForever(
            OurPartner::CACHE_KEY_ALL,
            static fn () => OurPartner::query()->get(['id', 'logo']),
        );
    }

    public function getSocialData(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_SOCIAL,
            'block.social.%',
        );
    }

    public function getSubscriptionIntro(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_SUBSCRIPTION_PAGE,
            ContentData::KEY_PAGE_SUBSCRIPTION_BLOCK_INTRO,
        );
    }

    public function getSubscriptionThankYouText(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_SUBSCRIPTION_THANK_YOU_PAGE,
            ContentData::KEY_PAGE_SUBSCRIPTION_THANK_YOU_BLOCK_TEXT,
        );
    }

    public function getSubscriptionInactiveText(): Collection
    {
        return $this->getData(
            ContentData::CACHE_KEY_SUBSCRIPTION_INACTIVE_PAGE,
            ContentData::KEY_PAGE_SUBSCRIPTION_INACTIVE_BLOCK_TEXT,
        );
    }

    protected function getData(string $cacheKey, string $key): Collection
    {
        return Cache::rememberForever(
            $cacheKey,
            static fn () => ContentData::query()
                ->where('key', 'like', $key)
                ->get(['key', 'value']),
        );
    }
}
