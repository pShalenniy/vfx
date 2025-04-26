<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\Region;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

use const false;
use const true;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeList(): void
    {
        UserCompany::factory()->create();
        User::factory()->create();

        $this->signInAsAdmin()
            ->getJson(URL::route('admin.user.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'first_name',
                        'last_name',
                        'email',
                        'company',
                        'country',
                        'region',
                        'city',
                        'job_title',
                        'phone_number',
                        'has_signatory',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('users', 2);
    }

    public function testUserCanNotStore(): void
    {
        $this->signInAsUser()
            ->post(
                URL::route('admin.user.store'),
                $this->userData() + $this->getUserLocationData(),
            )
            ->assertStatus(403);

        $this->assertDatabaseMissing('users', $this->userData());
    }

    public function testUserCanNotStoreWithInvalidEmail(): void
    {
        $userCompany = UserCompany::factory()->create();

        $userData = [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 1234,
            'company_id' => $userCompany,
            'job_title' => 'Actor',
            'phone_number' => '+380631111111',
            'has_signatory' => 1,
        ];

        $this->signInAsUser()
            ->post(
                URL::route('admin.user.store'),
                $userData + $this->getUserLocationData(),
            )
            ->assertStatus(403);

        $this->assertDatabaseMissing('users', $userData);
    }

    public function testUserCanStore(): void
    {
        $locationData = $this->getUserLocationData();

        $userData = $this->userData();

        $this->signInAsAdmin()
            ->post(
                URL::route('admin.user.store'),
                $userData + ['notify_user' => true] + $locationData + $this->getCompanyData(),
            )
            ->assertStatus(201);

        $this->assertDatabaseHas('users', $userData + $locationData);
    }

    public function testUserCanNotUpdate(): void
    {
        UserCompany::factory()->create();

        $user = User::factory()->create();
        $locationData = $this->getUserLocationData();

        $userEditData = $this->userEditData();

        $this->signInAsUser()
            ->patch(
                URL::route('admin.user.update', $user),
                $userEditData + $locationData + $this->getCompanyData(),
            )
            ->assertStatus(403);

        $this->assertDatabaseMissing('users', $userEditData + $locationData);
    }

    public function testUserCanUpdate(): void
    {
        UserCompany::factory()->create();

        $user = User::factory()->create();

        $locationData = $this->getUserLocationData();

        $department = Department::factory()->create()->only('id');

        /** @var \App\Models\Subscription $subscription */
        $subscription = $user->subscription()->create([
            'seats' => 1,
            'status' => Subscription::STATUS_PENDING_DEMO,
        ]);

        $subscription->departments()->sync($department);

        $this->assertDatabaseCount('department_subscription', 1);

        $subscriptionData = [
            'subscription' => [
                'seats' => 1,
                'departments' => $department,
                'status' => Subscription::STATUS_ACTIVE,
                'period' => Subscription::PERIOD_THREE_MONTH,
            ],
        ];

        $userEditData = $this->userEditData();

        $this->signInAsAdmin()
            ->patch(
                URL::route('admin.user.update', $user),
                $userEditData + $locationData + $subscriptionData,
            )
            ->assertStatus(201);

        $this->assertDatabaseCount('subscriptions', 1);
        $this->assertDatabaseCount('department_subscription', 1);
        $this->assertDatabaseCount('subscription_field_histories', 5);
    }

    public function testUserCanNotDelete(): void
    {
        UserCompany::factory()->create();

        $user = User::factory()->create();

        $this->signInAsUser()
            ->delete(URL::route('admin.user.destroy', $user))
            ->assertStatus(403);

        $this->assertDatabaseCount('users', 2);
    }

    public function testUserCanDelete(): void
    {
        UserCompany::factory()->create();

        $user = User::factory()->create();

        $this->signInAsAdmin()
            ->delete(URL::route('admin.user.destroy', $user))
            ->assertStatus(204);

        $this->assertDatabaseCount('users', 1);
    }

    public function testAdminCantFilterByHasExpiringField(): void
    {
        UserCompany::factory()->create();

        Subscription::factory(1)->create([
            'user_id' => User::factory()->create()->getKey(),
        ]);

        Subscription::factory(1)->create([
            'has_expired' => true,
            'user_id' => User::factory()->create()->getKey(),
        ]);

        $this->assertDatabaseCount('subscriptions', 2);

        $this->signInAsAdmin()
            ->getJson(URL::route('admin.user.list', ['subscription' => ['has_expired' => true]]))
            ->assertOk()
            ->assertJsonCount(1, 'data');

        $this->signInAsAdmin()
            ->getJson(URL::route('admin.user.list', ['subscription' => ['has_expired' => false]]))
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function testAdminCanResetHasSubscriptionField(): void
    {
        UserCompany::factory()->create();

        $subscription = Subscription::factory()->create([
            'has_expired' => true,
            'user_id' => User::factory()->create()->getKey(),
        ]);

        $this->assertDatabaseCount('subscriptions', 1);

        $this->signInAsAdmin()
            ->patch(URL::route('admin.subscription.update.has-expired', $subscription->getKey()))
            ->assertOk();
    }

    protected function userData(): array
    {
        return [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'test@example.com',
            'job_title' => 'Actor',
            'phone_number' => '+380631111111',
            'has_signatory' => 1,
        ];
    }

    protected function userEditData(): array
    {
        return [
            'first_name' => 'Lorem',
            'last_name' => 'Bar',
            'email' => 'test@example.com',
            'company' => [
                'name' => 'Test',
                'url' => 'www.example.com',
                'logo' => UploadedFile::fake()->image('noimage.png'),
            ],
            'job_title' => 'Actor',
            'phone_number' => '+380631111111',
            'has_signatory' => 1,
        ];
    }

    protected function getCompanyData(): array
    {
        return [
            'company' => [
                'name' => 'Test',
                'url' => 'www.example.com',
                'logo' => UploadedFile::fake()->image('noimage.png'),
            ],
        ];
    }

    protected function getUserLocationData(): array
    {
        $country = Country::factory()->create();
        $region = Region::factory()->create();
        $city = City::factory()->create();

        return [
            'country_id' => $country->getKey(),
            'region_id' => $region->getKey(),
            'city_id' => $city->getKey(),
        ];
    }
}
