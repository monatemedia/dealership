<?php // database/seeders/FuelTypeSeeder.php

namespace Database\Seeders;

use App\Models\FuelType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fuel Types from config
        FuelType::factory()
            ->count(count(config('lookups.fuel_types')))
            ->sequence(
                ...array_map(
                    fn($fuel) =>
                    ['name' => $fuel],
                    config('lookups.fuel_types')
                )
            )
            ->create();
    }
}
