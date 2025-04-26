<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\Common\Register\VerifyEmailMail;
use App\Models\City;
use App\Models\Country;
use App\Models\Region;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanRegister(): void
    {
        $country = Country::factory()->create();
        $region = Region::factory()->create();
        $city = City::factory()->create();

        $user = [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'test@example.com',
            'company' => [
                'name' => 'Test',
                'url' => 'www.example.com',
                'logo' => UploadedFile::fake()->image('noimage.png'),
            ],
            'country_id' => $country->getKey(),
            'region_id' => $region->getKey(),
            'city_id' => $city->getKey(),
            'job_title' => 'Actor',
            'phone_number' => '+380631111111',
            'has_signatory' => 1,
        ];

        Mail::fake();

        $this
            ->post(
                URL::route('sign-up.post'),
                $user + [
                    'password' => '$2y%04$8hn',
                    'password_confirmation' => '$2y%04$8hn',
                ],
            )
            ->assertStatus(201);

        Mail::assertQueued(VerifyEmailMail::class);
    }
}
