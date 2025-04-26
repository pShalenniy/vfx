<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\Admin\CandidateRepositoryContract;
use App\Repositories\Contracts\Admin\EmailTemplateSettingRepositoryContract;
use App\Repositories\Contracts\Admin\OurPartnerRepositoryContract;
use App\Repositories\Contracts\Admin\RoleRepositoryContract as AdminRoleRepositoryContract;
use App\Repositories\Contracts\Admin\SkillRepositoryContract;
use App\Repositories\Contracts\Admin\UserRepositoryContract as AdminUserRepositoryContract;
use App\Repositories\Contracts\Client\ActiveSessionRepositoryContract;
use App\Repositories\Contracts\Client\Candidate\ViewedCandidateRepositoryContract;
use App\Repositories\Contracts\Client\CandidateRepositoryContract as ClientCandidateRepositoryContract;
use App\Repositories\Contracts\Client\ShortlistRepositoryContract;
use App\Repositories\Contracts\Client\SubscriptionRepositoryContract;
use App\Repositories\Contracts\Client\UserRepositoryContract;
use App\Repositories\Contracts\Common\CityRepositoryContract as CommonCityRepositoryContract;
use App\Repositories\Contracts\Common\CompanyRepositoryContract;
use App\Repositories\Contracts\Common\ContentDataRepositoryContract;
use App\Repositories\Contracts\Common\JobRoleRepositoryContract;
use App\Repositories\Contracts\Common\PreferredLocationRepositoryContract;
use App\Repositories\Contracts\Common\PreferredWorkEnvironmentRepositoryContract;
use App\Repositories\Contracts\Common\RegionRepositoryContract;
use App\Repositories\Contracts\Common\RoleRepositoryContract;
use App\Repositories\Contracts\Common\SkillRepositoryContract as CommonSkillRepositoryContract;
use App\Repositories\Contracts\Common\TelevisionShowRepositoryContract;
use App\Repositories\Contracts\Common\TimezoneRepositoryContract;
use App\Repositories\Contracts\Common\UserCompanyRepositoryContract;
use App\Repositories\MySQL\Admin\CandidateRepository;
use App\Repositories\MySQL\Admin\EmailTemplateSettingRepository;
use App\Repositories\MySQL\Admin\OurPartnerRepository;
use App\Repositories\MySQL\Admin\RoleRepository as AdminRoleRepository;
use App\Repositories\MySQL\Admin\SkillRepository;
use App\Repositories\MySQL\Admin\UserRepository as AdminUserRepository;
use App\Repositories\MySQL\Client\ActiveSessionRepository;
use App\Repositories\MySQL\Client\Candidate\ViewedCandidateRepository;
use App\Repositories\MySQL\Client\CandidateRepository as ClientCandidateRepository;
use App\Repositories\MySQL\Client\ShortlistRepository;
use App\Repositories\MySQL\Client\SubscriptionRepository;
use App\Repositories\MySQL\Client\UserRepository;
use App\Repositories\MySQL\Common\CityRepository as CommonCityRepository;
use App\Repositories\MySQL\Common\CompanyRepository;
use App\Repositories\MySQL\Common\ContentDataRepository;
use App\Repositories\MySQL\Common\JobRoleRepository;
use App\Repositories\MySQL\Common\PreferredLocationRepository;
use App\Repositories\MySQL\Common\PreferredWorkEnvironmentRepository;
use App\Repositories\MySQL\Common\RegionRepository;
use App\Repositories\MySQL\Common\RoleRepository;
use App\Repositories\MySQL\Common\SkillRepository as CommonSkillRepository;
use App\Repositories\MySQL\Common\TelevisionShowRepository;
use App\Repositories\MySQL\Common\TimezoneRepository;
use App\Repositories\MySQL\Common\UserCompanyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected array $map = [
        ActiveSessionRepositoryContract::class => ActiveSessionRepository::class,
        AdminUserRepositoryContract::class => AdminUserRepository::class,
        AdminRoleRepositoryContract::class => AdminRoleRepository::class,
        CandidateRepositoryContract::class => CandidateRepository::class,
        ClientCandidateRepositoryContract::class => ClientCandidateRepository::class,
        CommonCityRepositoryContract::class => CommonCityRepository::class,
        CommonSkillRepositoryContract::class => CommonSkillRepository::class,
        CompanyRepositoryContract::class => CompanyRepository::class,
        ContentDataRepositoryContract::class => ContentDataRepository::class,
        EmailTemplateSettingRepositoryContract::class => EmailTemplateSettingRepository::class,
        JobRoleRepositoryContract::class => JobRoleRepository::class,
        OurPartnerRepositoryContract::class => OurPartnerRepository::class,
        PreferredLocationRepositoryContract::class => PreferredLocationRepository::class,
        PreferredWorkEnvironmentRepositoryContract::class => PreferredWorkEnvironmentRepository::class,
        RegionRepositoryContract::class => RegionRepository::class,
        RoleRepositoryContract::class => RoleRepository::class,
        ShortlistRepositoryContract::class => ShortlistRepository::class,
        SkillRepositoryContract::class => SkillRepository::class,
        SubscriptionRepositoryContract::class => SubscriptionRepository::class,
        TelevisionShowRepositoryContract::class => TelevisionShowRepository::class,
        TimezoneRepositoryContract::class => TimezoneRepository::class,
        UserCompanyRepositoryContract::class => UserCompanyRepository::class,
        UserRepositoryContract::class => UserRepository::class,
        ViewedCandidateRepositoryContract::class => ViewedCandidateRepository::class,
    ];

    public function register(): void
    {
        foreach ($this->map as $contract => $class) {
            $this->app->singleton($contract, $class);
        }
    }
}
