<?php // database/seeders/ProductionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Summary of ProductionSeeder
 * This seeder is responsible for populating the core application data
 */
class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            // Core application data ONLY
            VehicleCategorySeeder::class,
            VehicleTypeSeeder::class,
            FuelTypeSeeder::class,
            FeatureSeeder::class,

            // LocationSeeder::class, // This is for development (uses config)
            SouthAfricanCitySeeder::class, // This is for production (uses JSON)

            // ManufacturerSeeder::class, // This is for development (uses config)
            ProductionManufacturerSeeder::class, // This is for production (uses SQLite)
        ]);
    }
}
