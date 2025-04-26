<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(TestCandidatesSeeder::class);
        $this->call(TestAdminsSubscriptionsSeeder::class);
    }
}
