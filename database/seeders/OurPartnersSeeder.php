<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\OurPartner;
use Database\Seeders\Traits\UploadFileTrait;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

use function file_exists;

class OurPartnersSeeder extends Seeder
{
    use UploadFileTrait;

    public function run(): void
    {
        foreach ($this->getOurPartnersData() as $imageName) {
            $file = new UploadedFile("database/seeders/data/images/partners/{$imageName}", $imageName);

            try {
                $uploadFile = $this->storeUploadedFile($file, 'our-partners', OurPartner::STORAGE_DISK);

                OurPartner::query()->create(['logo' => $uploadFile]);
            } catch (Throwable $e) {
                if (isset($uploadFile) && file_exists($uploadFile)) {
                    Storage::disk(OurPartner::STORAGE_DISK)->delete($uploadFile);
                }
            }
        }
    }

    protected function getOurPartnersData(): array
    {
        return [
            'amazon.png',
            'netflix.png',
            'bbc.png',
            'lumiere.png',
            'virgin.png',
            'syfy.png',
            'telemundo.png',
            'nickelodeon.png',
        ];
    }
}
