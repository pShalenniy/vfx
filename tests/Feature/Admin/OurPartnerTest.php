<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\OurPartner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class OurPartnerTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanNotSeeList(): void
    {
        OurPartner::factory()->create();

        $this->assertDatabaseCount('our_partners', 1);

        $this->signInAsUser()->get(URL::route('admin.our-partner.list'))->assertStatus(403);
    }

    public function testUserCanSeeList(): void
    {
        OurPartner::factory()->create();

        $this->signInAsAdmin()
            ->getJson(URL::route('admin.our-partner.list'))
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'logo',
                        'created_at',
                    ],
                ],
            ]);

        $this->assertDatabaseCount('our_partners', 1);
    }

    public function testUserCanNotStore(): void
    {
        $this->signInAsUser()
            ->post(URL::route('admin.our-partner.store'), $this->ourPartnerData())
            ->assertStatus(403);

        $this->assertDatabaseCount('our_partners', 0);
    }

    public function testUserCanStore(): void
    {
        $this->signInAsAdmin()
            ->post(URL::route('admin.our-partner.store'), $this->ourPartnerData())
            ->assertStatus(201);

        $this->assertDatabaseCount('our_partners', 1);
    }

    public function testUserCanNotUpdate(): void
    {
        $ourPartner = OurPartner::factory()->create();

        $this->signInAsUser()
            ->patch(URL::route('admin.our-partner.update', $ourPartner), $this->ourPartnerEditData())
            ->assertStatus(403);

        $this->assertDatabaseCount('our_partners', 1);
    }

    public function testUserCanUpdate(): void
    {
        $ourPartner = OurPartner::factory()->create();

        $this->signInAsAdmin()
            ->patch(URL::route('admin.our-partner.update', $ourPartner), $this->ourPartnerEditData())
            ->assertStatus(201);

        $this->assertDatabaseCount('our_partners', 1);
    }

    public function testUserCanNotDelete(): void
    {
        $ourPartner = OurPartner::factory()->create($this->ourPartnerData());

        $this->signInAsUser()
            ->delete(URL::route('admin.our-partner.destroy', $ourPartner), $this->ourPartnerData())
            ->assertStatus(403);
    }

    public function testUserCanDelete(): void
    {
        $ourPartner = OurPartner::factory()->create($this->ourPartnerData());

        $this->signInAsAdmin()
            ->delete(URL::route('admin.our-partner.destroy', $ourPartner), $this->ourPartnerData())
            ->assertStatus(204);

        $this->assertDatabaseMissing('our_partners', $this->ourPartnerData());
    }

    protected function ourPartnerData(): array
    {
        return [
            'logo' => UploadedFile::fake()->image('noimage.png'),
        ];
    }

    protected function ourPartnerEditData(): array
    {
        return [
            'logo' => UploadedFile::fake()->image('noimage.png'),
        ];
    }
}
