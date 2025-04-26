<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class UserCompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word,
            'url' => $this->faker->url(),
            'logo' => UploadedFile::fake()->image('noimage.png'),
        ];
    }
}
