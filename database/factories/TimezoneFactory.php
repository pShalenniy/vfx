<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TimezoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->lexify('???'),
            'name' => $this->faker->unique()->word,
            'offset' => $this->faker->unique()->numberBetween(1, 255),
        ];
    }
}
