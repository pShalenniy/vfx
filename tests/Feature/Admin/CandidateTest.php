<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Mail\Admin\Candidate\CreatedMail;
use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Region;
use App\Models\Timezone;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

use const true;

class CandidateTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanSeeList(): void
    {
        Candidate::factory()->create($this->candidateData());

        $this->signInAsAdmin()
            ->getJson(URL::route('admin.candidate.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'picture',
                        'full_name',
                        'imdb_link',
                        'linkedin_link',
                        'instagram_link',
                        'twitter_link',
                        'next_availability',
                        'created_at',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanNotDelete(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->signInAsUser()
            ->delete(URL::route('admin.candidate.destroy', $candidate))
            ->assertStatus(403);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanNotStore(): void
    {
        $this->signInAsUser()
            ->post(URL::route('admin.candidate.store'), $this->candidateData())
            ->assertStatus(403);

        $this->assertDatabaseCount('candidates', 0);
    }

    public function testUserCanNotStoreWithInvalidAvailability(): void
    {
        $this->signInAsAdmin()
            ->postJson(URL::route('admin.candidate.store'), [
                'first_name' => 'Lorem',
                'last_name' => 'Ipsum',
                'next_availability' => 34566,
            ])
            ->assertStatus(422);

        $this->assertDatabaseCount('candidates', 0);
    }

    public function testUserCanNotStoreWithInvalidPhoneNumber(): void
    {
        $this->signInAsAdmin()
            ->postJson(URL::route('admin.candidate.store'), [
                'first_name' => 'Lorem',
                'last_name' => 'Ipsum',
                'next_availability' => '2022-11-14',
                'phone_number' => 'Test',
            ])
            ->assertStatus(422);

        $this->assertDatabaseCount('candidates', 0);
    }

    public function testUserCanNotStoreWithInvalidData(): void
    {
        $this->signInAsAdmin()
            ->postJson(URL::route('admin.candidate.store'))
            ->assertStatus(422);

        $this->assertDatabaseCount('candidates', 0);
    }

    public function testUserCanStore(): void
    {
        Mail::fake();

        $this->signInAsAdmin()
            ->post(URL::route('admin.candidate.store'), $this->candidateData())
            ->assertStatus(201);

        Mail::assertQueued(CreatedMail::class);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanNotUpdate(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->signInAsAdmin()
            ->patch(URL::route('admin.candidate.update', $candidate), $this->candidateEditData())
            ->assertStatus(201);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanNotUpdateWithInvalidFirstName(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->signInAsAdmin()
            ->patchJson(URL::route('admin.candidate.update', $candidate), [
                'first_name' => UploadedFile::fake()->image('noimage.png'),
                'last_name' => 'Test',
            ])
            ->assertStatus(422);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanNotUpdateWithInvalidLastName(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->signInAsAdmin()
            ->patchJson(URL::route('admin.candidate.update', $candidate), [
                'first_name' => 'Lorem',
                'last_name' => 1234,
            ])
            ->assertStatus(422);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanUpdate(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->signInAsAdmin()
            ->patch(URL::route('admin.candidate.update', $candidate), $this->candidateEditData())
            ->assertStatus(201);

        $this->assertDatabaseCount('candidates', 1);
    }

    public function testUserCanDelete(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $this->signInAsAdmin()
            ->delete(URL::route('admin.candidate.destroy', $candidate), $this->candidateData())
            ->assertStatus(204);

        $this->assertDatabaseCount('candidates', 0);
    }

    public function testUserCanMarkedCandidateStarred(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $starCandidateData = [
            'start_period' => Carbon::now()->format('Y-m-d'),
            'end_period' => Carbon::now()->format('Y-m-d'),
        ];

        $this->signInAsAdmin()
            ->post(URL::route('admin.candidate.mark-starred', $candidate->getKey()), $starCandidateData)
            ->assertStatus(201);

        $this->assertDatabaseCount('star_candidates', 1);
    }

    public function testUserCanFilterByStarCandidatesTest(): void
    {
        $candidate = Candidate::factory()->create($this->candidateData());

        $starCandidateData = [
            'start_period' => Carbon::now()->format('Y-m-d'),
            'end_period' => Carbon::now()->format('Y-m-d'),
        ];

        $this->signInAsAdmin()
            ->post(URL::route('admin.candidate.mark-starred', $candidate->getKey()), $starCandidateData)
            ->assertStatus(201);

        $this
            ->getJson(URL::route('admin.candidate.list'), ['starred_candidates' => true])
            ->assertJsonCount(1, 'data');

        $this->assertDatabaseCount('star_candidates', 1);
    }

    protected function candidateData(): array
    {
        $this->createCandidateInformationModels();

        return [
            'first_name' => 'Foo',
            'last_name' => 'Bar',
            'email' => 'test@example.com',
            'next_availability' => '2022-11-14',
        ];
    }

    protected function candidateEditData(): array
    {
        $this->createCandidateInformationModels();

        return [
            'first_name' => 'Lorem',
            'last_name' => 'Bar',
            'email' => 'test@example.com',
            'next_availability' => '2022-11-14',
        ];
    }

    protected function createCandidateInformationModels(): void
    {
        Country::factory()->create();
        Region::factory()->create();
        City::factory()->create();
        Timezone::factory()->create();
        Company::factory()->create();
    }
}
