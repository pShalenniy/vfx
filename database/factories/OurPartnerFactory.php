<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class OurPartnerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'logo' => UploadedFile::fake()->image('noimage.png'),
        ];
    }
}
