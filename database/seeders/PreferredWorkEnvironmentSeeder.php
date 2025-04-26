<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PreferredWorkEnvironment;
use Illuminate\Database\Seeder;

class PreferredWorkEnvironmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getPreferredWorkEnvironments() as $data) {
            PreferredWorkEnvironment::query()->updateOrCreate(
                ['name' => $data],
            );
        }
    }

    protected function getPreferredWorkEnvironments(): array
    {
        return [
            'Start-up company',
            'Mid sized company',
            'Large company',
        ];
    }
}
