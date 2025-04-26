<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('role:setup');

        $this->call(ContentDataSeeder::class);
        $this->call(EmailTemplateSettingsSeeder::class);
        $this->call(OurPartnersSeeder::class);
        $this->call(PreferredSectorsSeeder::class);
        $this->call(PreferredWorkEnvironmentSeeder::class);
    }
}
