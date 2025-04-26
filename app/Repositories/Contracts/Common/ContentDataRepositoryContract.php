<?php

declare(strict_types=1);

namespace App\Repositories\Contracts\Common;

use Illuminate\Database\Eloquent\Collection;

interface ContentDataRepositoryContract
{
    public function getContactData(): Collection;

    public function getDataForAboutUsPage(): Collection;

    public function getDataForActiveSessionsPage(): Collection;

    public function getDataForContactUsPage(): Collection;

    public function getDataForHomePage(): Collection;

    public function getDataForPrivacyPolicyPage(): Collection;

    public function getDataForTermsAndConditionsPage(): Collection;

    public function getLogoData(): Collection;

    public function getOurPartners(): Collection;

    public function getSocialData(): Collection;

    public function getSubscriptionIntro(): Collection;

    public function getSubscriptionThankYouText(): Collection;

    public function getSubscriptionInactiveText(): Collection;
}
