<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Department;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use McMatters\LaravelTracking\Http\Middleware\Track;

use const null;

abstract class TestCase extends BaseTestCase
{
    protected ?User $user;

    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(Track::class);

        $this->disableHeimdall();
    }

    protected function signInAsUser(?User $user = null): self
    {
        UserCompany::factory()->create();

        $user ??= User::factory()->create();

        $this->user = $user;

        $this->prepareUserSubscription($user);

        $this->actingAs($user);

        return $this;
    }

    protected function signInAsAdmin(?User $user = null): self
    {
        Artisan::call('role:setup');

        UserCompany::factory()->create();

        $user = $user ?: User::factory()->create();
        $user->attachRole('super-admin');

        $this->user = $user;

        $this->actingAs($user);

        return $this;
    }

    protected function prepareUserSubscription(User $user): void
    {
        $department = Department::factory()->create();

        $subscription = Subscription::factory()->create(['user_id' => $user->getKey()]);

        $subscription->departments()->sync($department->getKey());
    }

    protected function disableHeimdall(): void
    {
        Config::set('heimdall.observer.models', []);
    }
}
