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
        // Basic users
        User::factory()->count(3)->create(); // Create some basic users

        // Users with vehicles
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
