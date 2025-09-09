<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use App\Models\VehicleImage;
use App\Models\VehicleType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Manufacturer;
use App\Models\Model;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call Seeders
        $this->call([
            VehicleCategorySeeder::class,
        ]);


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

        // Provinces & Cities from config
        foreach (config('provinces.provinces') as $province => $cities) {
            Province::factory()
                ->state(['name' => $province])
                ->has(
                    City::factory()
                        ->count(count($cities))
                        ->sequence(...array_map(fn($city) => ['name' => $city], $cities))
                )
                ->create();
        }

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

        // Users, Vehicles, Images, Features
        User::factory()->count(3)->create();

        User::factory()
            ->count(2)
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
