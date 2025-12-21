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
            // Ensure storage symlink exists FIRST
            StorageLinkSeeder::class,

            // Core application data ONLY
            CategorySeeder::class, // Runs first (creates section/categories)
            VehicleTypeSeeder::class,

            FuelTypeSeeder::class, // Seed FuelTypes
            TransmissionSeeder::class, // Seed Transmissions
            DrivetrainSeeder::class, // Seed Drivetrains
            ColorSeeder::class,
            InteriorSeeder::class,
            AccidentHistorySeeder::class,
            ServiceHistorySeeder::class,
            ConditionSeeder::class,
            FeatureSeeder::class,
            OwnershipPaperworkSeeder::class, // Add this line

            // Linkers (must run after categories and types are seeded)
            CategoryFuelTypeSeeder::class,
            CategoryTransmissionSeeder::class, // Add new linker
            CategoryDrivetrainSeeder::class, // Add new linker
            CategoryColorSeeder::class,
            CategoryInteriorSeeder::class,
            CategoryAccidentHistorySeeder::class,
            CategoryFeatureSeeder::class, // Add new linker

            SouthAfricanCitySeeder::class,
            ProductionManufacturerSeeder::class,
        ]);
    }
}
