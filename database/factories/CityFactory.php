<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'region_id' => Region::query()->inRandomOrder()->value('id'),
        ];
    }
}
