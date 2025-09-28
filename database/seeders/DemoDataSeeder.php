<?php // database/seeders/DemoDataSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleImage;
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
        // Users
        User::factory()->count(3)->create(); // Create some basic users

        User::factory()
            ->count(2) // Create users with vehicles
            ->has(
                Vehicle::factory()
                    ->count(50)
                    ->has(
                        VehicleImage::factory()
                            ->count(5)
                            ->sequence(fn(Sequence $sequence) => [
                                'position' => ($sequence->index % 5) + 1,
                            ]),
                        'images'
                    )
                    ->hasFeatures(),
                'favouriteVehicles'
            )
            ->create();
    }
}
