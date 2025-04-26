<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Region;
use App\Models\Timezone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use McMatters\Helpers\Helpers\ClassHelper;

class CandidateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->word,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
            'city_id' => City::query()->inRandomOrder()->value('id'),
            'region_id' => Region::query()->inRandomOrder()->value('id'),
            'country_id' => Country::query()->inRandomOrder()->value('id'),
            'timezone_id' => Timezone::query()->inRandomOrder()->value('id'),
            'company_id' => Company::query()->inRandomOrder()->value('id'),
            'budget_of_biggest_show' => Arr::random(ClassHelper::getConstantsStartWith(
                Candidate::class,
                'BUDGET_OF_BIGGEST_SHOW_',
            )),
            'phone_number' => $this->faker->numberBetween(1, 200000000000000),
            'gross_annual_salary' => $this->faker->numberBetween(1, 2000),
            'week_rate' => $this->faker->numberBetween(1, 2000),
            'day_rate' => $this->faker->numberBetween(1, 2000),
            'would_like_work_on' => $this->faker->text,
            'vfx_notes' => $this->faker->text,
            'picture' => UploadedFile::fake()->image('noimage.png'),
            'current_work' => $this->faker->text,
            'previous_work' => $this->faker->text,
            'professional_interest' => $this->faker->text,
            'next_availability' => $this->faker->dateTime()->format('Y-m-d H:m:s'),
            'source' => Arr::random(ClassHelper::getConstantsStartWith(Candidate::class, 'SOURCE_')),
        ];
    }
}
