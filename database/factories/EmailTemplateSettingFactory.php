<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailTemplateSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => $this->faker->word,
            'subject' => $this->faker->word,
            'body' => $this->faker->text,
            'emails' => [
                [
                    $this->faker->unique()->safeEmail,
                    $this->faker->unique()->safeEmail,
                    $this->faker->unique()->safeEmail,
                    $this->faker->unique()->safeEmail,
                    $this->faker->unique()->safeEmail,
                ],
            ],
        ];
    }
}
