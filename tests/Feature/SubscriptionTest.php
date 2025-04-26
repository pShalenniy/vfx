<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\Client\Subscription\AdminRenewFirstPausedPeriodMail;
use App\Mail\Client\Subscription\AdminRenewPausedMail;
use App\Mail\Client\Subscription\AdminRequestChangeMail;
use App\Mail\Client\Subscription\AdminRequestPauseMail;
use App\Mail\Client\Subscription\ClientRenewFirstPausedPeriodMail;
use App\Mail\Client\Subscription\ClientRenewPausedMail;
use App\Mail\Client\Subscription\ClientRequestChangeMail;
use App\Mail\Client\Subscription\ProcessRequestPauseMail;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Subscription;
use App\Models\SubscriptionFieldHistory;
use App\Models\User;
use App\Models\UserCompany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCantSendSubscriptionRequest(): void
    {
        $subscription = [
            'seats' => 5,
            'departments' => $this->getDepartment(),
        ];

        $this->actingAs($this->getUser())
            ->postJson(URL::route('subscription.store'), $subscription)
            ->assertStatus(201);
    }

    public function testUserCantSendSubscriptionRequestWithInvalidSeats(): void
    {
        $subscription = [
            'seats' => 'Test',
            'departments' => $this->getDepartment(),
        ];

        $this->actingAs($this->getUser())
            ->postJson(URL::route('subscription.store'), $subscription)
            ->assertStatus(422);
    }

    public function testUserCantSendSubscriptionRequestWithInvalidDepartments(): void
    {
        $subscription = [
            'seats' => 1,
            'departments' => 'Test',
        ];

        $this->actingAs($this->getUser())
            ->postJson(URL::route('subscription.store'), $subscription)
            ->assertStatus(422);
    }

    public function testUserCantSendRequestForChangeSubscription(): void
    {
        Artisan::call('db:seed --class=EmailTemplateSettingsSeeder');

        UserCompany::factory()->create();

        $user = User::factory()->create();

        Subscription::factory()->create(['user_id' => $user->getKey()]);

        Mail::fake();

        $this->actingAs($user)
            ->post(URL::route('subscription.request-change'))
            ->assertStatus(204);

        Mail::assertQueued(ClientRequestChangeMail::class);
        Mail::assertQueued(AdminRequestChangeMail::class);
    }

    public function testUserCantSendRequestForPauseSubscription(): void
    {
        Artisan::call('db:seed --class=EmailTemplateSettingsSeeder');

        UserCompany::factory()->create();

        $user = User::factory()->create();

        Subscription::factory()->create(['user_id' => $user->getKey()]);

        Mail::fake();

        $this->actingAs($user)
            ->post(URL::route('subscription.request-pause'))
            ->assertStatus(204);

        Mail::assertQueued(ProcessRequestPauseMail::class);
        Mail::assertQueued(AdminRequestPauseMail::class);
    }

    public function testUsersReceiveRenewPausedNotifications(): void
    {
        $now = Carbon::now();

        Artisan::call('db:seed --class=EmailTemplateSettingsSeeder', []);

        UserCompany::factory()->create();

        $user = User::factory()->create();

        $subscription = Subscription::factory()->create([
            'user_id' => $user->getKey(),
            'status' => Subscription::STATUS_PAUSED,
            'starts_at' => $now->clone()->subMonths(Subscription::PAUSE_MONTH_PERIOD)->startOfDay(),
            'ends_at' => $now->clone()->addMonth(),
        ]);

        $this->createSubscriptionHistory($subscription->getKey(), Subscription::PAUSE_MONTH_PERIOD);

        Mail::fake();

        Artisan::call('subscription:renew:paused');

        Mail::assertQueued(ClientRenewPausedMail::class);
        Mail::assertQueued(AdminRenewPausedMail::class);
    }

    public function testEndPausedPeriodReminderNotifications(): void
    {
        $now = Carbon::now();

        Artisan::call('db:seed --class=EmailTemplateSettingsSeeder', []);

        UserCompany::factory()->create();

        $user = User::factory()->create();

        $subscription = Subscription::factory()->create([
            'user_id' => $user->getKey(),
            'status' => Subscription::STATUS_PAUSED,
            'starts_at' => $now->clone()->subMonths(Subscription::PERIOD_THREE_MONTH)->startOfDay(),
            'ends_at' => $now->clone()->addDays(Subscription::REMIND_PERIOD_ONE_MONTH),
            'pause_count' => 1,
        ]);

        SubscriptionFieldHistory::factory()->create([
            'subscription_id' => $subscription->getKey(),
            'field' => 'status',
            'previous_value' => Subscription::STATUS_ACTIVE,
            'new_value' => Subscription::STATUS_PAUSED,
            'created_at' => Carbon::now()
                ->clone()
                ->clone()
                ->addDays(Subscription::REMIND_PERIOD_ONE_MONTH)
                ->subMonths(Subscription::PAUSE_MONTH_PERIOD),
        ]);

        $this->createSubscriptionHistory($subscription->getKey(), Subscription::PERIOD_THREE_MONTH);

        Mail::fake();

        Artisan::call('subscription:end-paused-period:reminder');

        Mail::assertQueued(ClientRenewFirstPausedPeriodMail::class);
        Mail::assertQueued(AdminRenewFirstPausedPeriodMail::class);
    }

    protected function createSubscriptionHistory(int $subscriptionId, int $period): void
    {
        SubscriptionFieldHistory::factory()->create([
            'subscription_id' => $subscriptionId,
            'field' => 'status',
            'previous_value' => Subscription::STATUS_ACTIVE,
            'new_value' => Subscription::STATUS_PAUSED,
            'created_at' => Carbon::now()
                ->clone()
                ->subMonths($period)
                ->endOfDay(),
        ]);
    }

    protected function getDepartment(): array
    {
        return Department::factory()->create()->only('id');
    }

    protected function getUser(): User
    {
        $preferredJobRole = JobRole::factory()->create()->value('id');

        UserCompany::factory()->create();

        $user = User::factory()->create();

        $user->preferredJobRoles()->sync($preferredJobRole);

        return $user;
    }
}
