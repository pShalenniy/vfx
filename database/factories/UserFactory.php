<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use App\Models\UserCompany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->word,
            'email' => $this->faker->unique()->safeEmail(),
            'company_id' => UserCompany::query()->inRandomOrder()->value('id'),
            'city_id' => City::query()->inRandomOrder()->value('id'),
            'region_id' => Region::query()->inRandomOrder()->value('id'),
            'country_id' => Country::query()->inRandomOrder()->value('id'),
            'job_title' => $this->faker->word,
            'phone_number' => "+{$this->faker->numberBetween(1, 1000000000)}",
            'has_signatory' => $this->faker->boolean,
            'password' => 'password',
            'email_verified_at' => Carbon::now(),
        ];
    }
}
