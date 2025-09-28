<?php // database/seeders/ManufacturerSeeder.php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Model;
use Illuminate\Database\Seeder;

/**
 * Summary of ManufacturerSeeder
 * This seeder populates the manufacturers and their associated models
 */
class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Manufacturers & Models from config
        foreach (config('manufacturers.manufacturers') as $manufacturer => $models) {
            Manufacturer::factory()
                ->state(['name' => $manufacturer])
                ->has(
                    Model::factory()
                        ->count(count($models))
                        ->sequence(...array_map(fn($model) => ['name' => $model], $models))
                )
                ->create();
        }
    }
}
