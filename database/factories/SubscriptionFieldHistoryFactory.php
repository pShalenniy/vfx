<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFieldHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subscription_id' => Subscription::query()->inRandomOrder()->value('id'),
            'field' => $this->faker->unique()->word,
            'previous_value' => $this->faker->unique()->word,
            'new_value' => $this->faker->unique()->word,
            'created_at' => Carbon::now(),
        ];
    }
}
