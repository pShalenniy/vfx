<?php

declare(strict_types=1);

namespace Tests\Feature\Candidate;

use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\JobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Models\Region;
use App\Models\Skill;
use App\Models\TelevisionShow;
use App\Models\Timezone;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

use const true;

class AccountSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function testAnonymousUserCanNotSeeAccountSettingsPage(): void
    {
        $this->get(URL::route('candidate.account-settings.show'))->assertStatus(302);
    }

    public function testsAnonymousUserCanNotUpdateProfile(): void
    {
        $this
            ->patch(URL::route('candidate.account-settings.update'), $this->candidateMainInformationData())
            ->assertStatus(302);
    }

    public function testCandidateCanSeeAccountSettingsPage(): void
    {
        $candidate = Candidate::factory()->create();

        $this->actingAs($candidate)
            ->get(URL::route('candidate.account-settings.show'))
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testsCandidateCanUpdateProfileContactInformation(): void
    {
        $candidate = Candidate::factory()->create();

        $this->actingAs($candidate)
            ->patch(URL::route('candidate.account-settings.update'), $this->candidateContactData())
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testsCandidateCanUpdateProfileGeneralInformation(): void
    {
        $candidate = Candidate::factory()->create();

        $this->actingAs($candidate)
            ->patch(URL::route('candidate.account-settings.update'), $this->candidateGeneralData())
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testsCandidateCanUpdateProfileInterestsInformation(): void
    {
        $candidate = Candidate::factory()->create();

        $this->actingAs($candidate)
            ->patch(URL::route('candidate.account-settings.update'), $this->candidateInterestsData())
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testsCandidateCanUpdateProfileMainInformation(): void
    {
        $candidate = Candidate::factory()->create();

        $this->actingAs($candidate)
            ->patch(URL::route('candidate.account-settings.update'), $this->candidateMainInformationData())
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testsCandidateCanUpdateProfilePassword(): void
    {
        $candidate = Candidate::factory()->create();

        $this->actingAs($candidate)
            ->patch(
                URL::route('candidate.account-settings.update'),
                [
                    'password' => 'Test123#',
                    'password_confirmation' => 'Test123#',
                    'blockKey' => 'password',
                ],
            )
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testsCandidateCanUpdateProfilePersonalInformation(): void
    {
        $candidate = Candidate::factory()->create();

        $this->actingAs($candidate)
            ->patch(URL::route('candidate.account-settings.update'), $this->candidatePersonalData())
            ->assertOk();

        $this->assertDatabaseCount('candidates', 1);
    }

    protected function candidateContactData(): array
    {
        return [
            'imdb_link' => 'https://www.imdb.com/name/nm8463347/?ref_=tt_cl_t_2',
            'linkedin_link' => 'https://www.linkedin.com/test',
            'instagram_link' => 'https://www.instagram.com/nina/',
            'twitter_link' => 'https://www.twitter.com/test',
            'phone_number' => '+380630000000',
            'blockKey' => 'contact',
        ];
    }

    protected function candidateGeneralData(): array
    {
        $televisionShow = TelevisionShow::factory()->create()->first();
        $skill = Skill::factory()->create()->first();
        $company = Company::factory()->create()->first();

        return [
            'commercial_experience' => '2023',
            'budget_of_biggest_show' => Candidate::BUDGET_OF_BIGGEST_SHOW_0_25M,
            'television_show' => [
                [
                    'id' => $televisionShow->getKey(),
                    'name' => $televisionShow->getAttribute('name'),
                ],
            ],
            'skills' => [
                [
                    'id' => $skill->getKey(),
                    'title' => $skill->getAttribute('title'),
                    'level' => CandidateSkill::LEVEL_INTERMEDIATE,
                ],
            ],
            'company' => [
                'id' => $company->getKey(),
                'name' => $company->getAttribute('name'),
            ],
            'blockKey' => 'general',
        ];
    }

    protected function candidateInterestsData(): array
    {
        $jobRole = JobRole::factory()->create()->first();
        $skill = Skill::factory()->create()->first();
        $preferredLocation = PreferredLocation::factory()->create()->first();
        $preferredSector = PreferredSector::factory()->create()->first();
        $preferredWorkEnvironment = PreferredWorkEnvironment::factory()->create()->first();

        return [
            'travel_availability' => true,
            'preferred_location' => [
                [
                    'id' => $preferredLocation->getKey(),
                    'name' => $preferredLocation->getAttribute('name'),
                ],
            ],
            'preferred_sectors' => [
                [
                    'id' => $preferredSector->getKey(),
                    'name' => $preferredSector->getAttribute('name'),
                ],
            ],
            'desired_job_roles' => [
                [
                    'id' => $jobRole->getKey(),
                    'name' => $jobRole->getAttribute('name'),
                ],
            ],
            'next_promotion_job_roles' => [
                [
                    'id' => $jobRole->getKey(),
                    'name' => $jobRole->getAttribute('name'),
                ],
            ],
            'want_work_with_tools' => [
                [
                    'id' => $skill->getKey(),
                    'name' => $skill->getAttribute('name'),
                ],
            ],
            'want_learn_skills' => [
                [
                    'id' => $skill->getKey(),
                    'name' => $skill->getAttribute('name'),
                ],
            ],
            'preferred_work_environments' => [
                [
                    'id' => $preferredWorkEnvironment->getKey(),
                    'name' => $preferredWorkEnvironment->getAttribute('name'),
                ],
            ],
            'would_like_work_on' => 'Test',
            'blockKey' => 'interests',
        ];
    }

    protected function candidateMainInformationData(): array
    {
        $jobRole = JobRole::factory()->create()->first();
        $country = Country::factory()->create()->first();
        $region = Region::factory()->create()->first();
        $city = City::factory()->create()->first();
        $timezone = Timezone::factory()->create()->first();

        return [
            'first_name' => 'Lorem',
            'last_name' => 'Bar',
            'city_id' => $city->getKey(),
            'region_id' => $region->getKey(),
            'country_id' => $country->getKey(),
            'nationalities' => [$country->getKey()],
            'timezone_id' => $timezone->getKey(),
            'next_availability' => '2022-11-14',
            'alternative_citizenship_residencies' => Country::factory(3)->create()->modelKeys(),
            'current_job_roles' => [
                [
                    'id' => $jobRole->getKey(),
                    'name' => $jobRole->getAttribute('name'),
                ],
            ],
            'blockKey' => 'main_information',
        ];
    }

    protected function candidatePersonalData(): array
    {
        return [
            'gross_annual_salary' => 20000,
            'week_rate' => 5000,
            'day_rate' => 1000,
            'salary_rate_currency' => Candidate::SALARY_RATE_CURRENCY_FRANC,
            'blockKey' => 'personal',
        ];
    }
}
