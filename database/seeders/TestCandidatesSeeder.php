<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Region;
use App\Models\Timezone;
use Illuminate\Database\Seeder;

class TestCandidatesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getCandidatesData() as $data) {
            Candidate::query()->create($data);
        }
    }

    protected function getCandidatesData(): array
    {
        return [
            [
                'first_name' => 'John',
                'last_name' => 'Dou',
                'email' => 'j.dou@gmail.com',
                'city_id' => City::query()->inRandomOrder()->value('id'),
                'region_id' => Region::query()->inRandomOrder()->value('id'),
                'country_id' => Country::query()->inRandomOrder()->value('id'),
                'timezone_id' => Timezone::query()->inRandomOrder()->value('id'),
                'company_id' => Company::query()->inRandomOrder()->value('id'),
                'budget_of_biggest_show' => Candidate::BUDGET_OF_BIGGEST_SHOW_0_25M,
                'phone_number' => 380630000000,
                'gross_annual_salary' => 10000,
                'week_rate' => 1000,
                'day_rate' => 200,
                'would_like_work_on' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'linkedin_link' => 'https://www.linkedin.com/in/john-dou/',
                'current_work' => 'Google',
                'professional_interest' => 'IT technologies, programming languages',
                'next_availability' => '2023-04-30',
                'source' => Candidate::SOURCE_DATABASE,
            ],
            [
                'first_name' => 'Marcella',
                'last_name' => 'Huels',
                'email' => 'm.huels@gmail.com',
                'city_id' => City::query()->inRandomOrder()->value('id'),
                'region_id' => Region::query()->inRandomOrder()->value('id'),
                'country_id' => Country::query()->inRandomOrder()->value('id'),
                'timezone_id' => Timezone::query()->inRandomOrder()->value('id'),
                'company_id' => Company::query()->inRandomOrder()->value('id'),
                'budget_of_biggest_show' => Candidate::BUDGET_OF_BIGGEST_SHOW_25M_50M,
                'phone_number' => 380630000001,
                'gross_annual_salary' => 12000,
                'week_rate' => 1200,
                'day_rate' => 240,
                'would_like_work_on' => 'Lorem Ipsum more recently with desktop publishing software like Aldus PageMaker.',
                'linkedin_link' => 'https://www.linkedin.com/in/vikki-test-5b530a268',
                'current_work' => 'Apple',
                'professional_interest' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'next_availability' => '2023-04-20',
                'source' => Candidate::SOURCE_DATABASE,
            ],
            [
                'first_name' => 'Joann',
                'last_name' => 'Osinski',
                'email' => 'joann-osinski@google.com',
                'city_id' => City::query()->inRandomOrder()->value('id'),
                'region_id' => Region::query()->inRandomOrder()->value('id'),
                'country_id' => Country::query()->inRandomOrder()->value('id'),
                'timezone_id' => Timezone::query()->inRandomOrder()->value('id'),
                'company_id' => Company::query()->inRandomOrder()->value('id'),
                'budget_of_biggest_show' => Candidate::BUDGET_OF_BIGGEST_SHOW_25M_50M,
                'phone_number' => 380630000021,
                'gross_annual_salary' => 10000,
                'week_rate' => 1000,
                'day_rate' => 200,
                'would_like_work_on' => 'Lorem Ipsum more recently with desktop publishing software like Aldus PageMaker.',
                'linkedin_link' => 'https://www.linkedin.com/in/joann-osinski',
                'current_work' => 'Amazon',
                'professional_interest' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'next_availability' => '2023-04-22',
                'source' => Candidate::SOURCE_DATABASE,
            ],
            [
                'first_name' => 'Allen',
                'last_name' => 'Brown',
                'email' => 'allen-brown@gmail.com',
                'city_id' => City::query()->inRandomOrder()->value('id'),
                'region_id' => Region::query()->inRandomOrder()->value('id'),
                'country_id' => Country::query()->inRandomOrder()->value('id'),
                'timezone_id' => Timezone::query()->inRandomOrder()->value('id'),
                'company_id' => Company::query()->inRandomOrder()->value('id'),
                'budget_of_biggest_show' => Candidate::BUDGET_OF_BIGGEST_SHOW_100M_150M,
                'phone_number' => 380630000021,
                'gross_annual_salary' => 10000,
                'week_rate' => 1000,
                'day_rate' => 200,
                'would_like_work_on' => 'Lorem Ipsum more recently with desktop publishing software like Aldus PageMaker.',
                'linkedin_link' => 'https://linkedin.com/in/allen-brown',
                'professional_interest' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'next_availability' => '2023-04-22',
                'source' => Candidate::SOURCE_DATABASE,
            ],
            [
                'first_name' => 'Tom',
                'last_name' => 'Beer',
                'email' => 'tombeer@gmail.com',
                'city_id' => City::query()->inRandomOrder()->value('id'),
                'region_id' => Region::query()->inRandomOrder()->value('id'),
                'country_id' => Country::query()->inRandomOrder()->value('id'),
                'timezone_id' => Timezone::query()->inRandomOrder()->value('id'),
                'company_id' => Company::query()->inRandomOrder()->value('id'),
                'budget_of_biggest_show' => Candidate::BUDGET_OF_BIGGEST_SHOW_100M_150M,
                'phone_number' => 380630000021,
                'gross_annual_salary' => 10000,
                'week_rate' => 1000,
                'day_rate' => 200,
                'would_like_work_on' => 'Lorem Ipsum more recently with desktop publishing software like Aldus PageMaker.',
                'linkedin_link' => 'https://linkedin.com/in/tom-beer',
                'professional_interest' => 'Contrary to popular belief, Lorem Ipsum is not simply random text.',
                'next_availability' => '2023-04-22',
                'source' => Candidate::SOURCE_DATABASE,
            ],
        ];
    }
}
