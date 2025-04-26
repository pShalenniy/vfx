<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PreferredSector;
use Illuminate\Database\Seeder;

class PreferredSectorsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getPreferredSectorData() as $data) {
            PreferredSector::query()->create(['name' => $data]);
        }
    }

    protected function getPreferredSectorData(): array
    {
        return [
            'Animation Studios',
            'Architecture Visualization',
            'Augmented Reality',
            'Automotive',
            'Computer Games',
            'Digital Human Groups',
            'Fashion Visualization',
            'Media Agencies',
            'Medical Equipment and Systems',
            'Product Visualization',
            'Production companies',
            'Production Services',
            'Space and Aerospace Avionics',
            'Virtual Production Studios',
            'Virtual Reality',
            'Visual Effects Studios',
        ];
    }
}
