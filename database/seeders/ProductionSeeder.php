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
            CategorySeeder::class, // Runs first (creates Main/Sub Categories)
            VehicleTypeSeeder::class,

            FuelTypeSeeder::class, // Seed FuelTypes
            TransmissionSeeder::class, // Seed Transmissions
            DrivetrainSeeder::class, // Seed Drivetrains
            FeatureSeeder::class,
            OwnershipPaperworkSeeder::class, // Add this line

            // Linkers (must run after categories and types are seeded)
            SubcategoryFuelTypeSeeder::class,
            SubcategoryTransmissionSeeder::class, // Add new linker
            SubcategoryDrivetrainSeeder::class, // Add new linker
            SubcategoryFeatureSeeder::class, // Add new linker

            SouthAfricanCitySeeder::class,
            ProductionManufacturerSeeder::class,
        ]);
    }
}
