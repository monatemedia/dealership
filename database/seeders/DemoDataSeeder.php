<?php // database/seeders/DemoDataSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

/**
 * Summary of DemoDataSeeder
 * This seeder populates the database with fake/demo data for development and testing purposes.
 */
class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Seeding demo data...');

        // Disable Scout auto-indexing during seeding
        // We'll manually import to Typesense after seeding is complete
        Vehicle::withoutSyncingToSearch(function () {
            $this->seedData();
        });

        $this->command->newLine();
        $this->command->info('✓ Demo data seeded successfully');
        $this->command->info('Run "php artisan scout:import App\\\Models\\\Vehicle" to index in Typesense');
    }

    protected function seedData(): void
    {
        // Basic users
        $this->command->info('Creating basic users...');
        User::factory()->count(3)->create();

        // Users with vehicles
        $this->command->info('Creating users with vehicles...');
        User::factory()
            ->count(2) // Create users with vehicles
            ->has(
                // Each user has 50 vehicles
                Vehicle::factory()
                    ->count(50)
                    ->has(
                        // Each vehicle has 5 images with positions 1 to 5
                        VehicleImage::factory()
                            ->count(5)
                            ->sequence(fn(Sequence $sequence) => [
                                'position' => ($sequence->index % 5) + 1,
                            ]),
                        'images'
                    )
                    ->afterCreating(function (Vehicle $vehicle) {
                        // Random features
                        $featureIds = Feature::inRandomOrder()
                            ->take(rand(2, 6))
                            ->pluck('id');

                        $vehicle->features()->attach($featureIds);
                    }),
                'favouriteVehicles'
            )
            ->create();
    }
}
