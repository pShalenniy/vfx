<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\OurPartner;
use App\Models\Pivot\CandidateShortlist;
use App\Models\PreferredSector;
use App\Models\Region;
use App\Models\Skill;
use App\Models\Subscription;
use App\Models\Timezone;
use App\Observers\CandidateObserver;
use App\Observers\CandidateShortlistObserver;
use App\Observers\CityObserver;
use App\Observers\CompanyObserver;
use App\Observers\CountryObserver;
use App\Observers\OurPartnerObserver;
use App\Observers\PreferredSectorObserver;
use App\Observers\RegionObserver;
use App\Observers\SkillObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\TimezoneObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        //
    ];

    protected $observers = [
        Candidate::class => CandidateObserver::class,
        CandidateShortlist::class => CandidateShortlistObserver::class,
        City::class => CityObserver::class,
        Company::class => CompanyObserver::class,
        Country::class => CountryObserver::class,
        OurPartner::class => OurPartnerObserver::class,
        PreferredSector::class => PreferredSectorObserver::class,
        Region::class => RegionObserver::class,
        Skill::class => SkillObserver::class,
        Subscription::class => SubscriptionObserver::class,
        Timezone::class => TimezoneObserver::class,
    ];

    public function boot(): void
    {
        //
    }
}
