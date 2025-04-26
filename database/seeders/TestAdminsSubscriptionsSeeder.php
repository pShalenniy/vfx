<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class TestAdminsSubscriptionsSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('department:collect:tinsel-town');

        $departments = Department::query()->pluck('id')->all();

        $adminUsers = User::query()->where('role_id', 1)->get();

        foreach ($adminUsers as $adminUser) {
            /** @var \App\Models\Subscription $subscription */
            $subscription = Subscription::query()->create([
                'user_id' => $adminUser->getKey(),
                'seats' => 2,
            ]);

            $subscription->departments()->sync($departments);
        }
    }
}
