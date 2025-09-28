<?php // database/seeders/VehicleTypeSeeder.php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Summary of VehicleTypeSeeder
 * This seeder populates the vehicle types from configuration
 */
class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vehicle Types from config
        VehicleType::factory()
            ->count(count(config('lookups.vehicle_types')))
            ->sequence(
                ...array_map(
                    fn($type) =>
                    ['name' => $type],
                    config('lookups.vehicle_types')
                )
            )
            ->create();
    }
}
