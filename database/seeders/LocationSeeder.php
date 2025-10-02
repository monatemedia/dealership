<?php // database/seeders/LocationSeeder.php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Provinces & Cities from config
        foreach (config('locations.provinces') as $province => $data) {
            // Get base coordinates for this province from config
            $baseCoords = $data['coordinates'];
            $cities = $data['cities'];

            Province::factory()
                ->state(['name' => $province])
                ->has(
                    City::factory()
                        ->count(count($cities))
                        ->sequence(...array_map(function($city) use ($baseCoords) {
                            return [
                                'name' => $city,
                                'latitude' => $this->generateLatitude($baseCoords['lat']),
                                'longitude' => $this->generateLongitude($baseCoords['lng']),
                            ];
                        }, $cities))
                )
                ->create();
        }
    }

    /**
     * Generate a random latitude near the base coordinate
     * Adds/subtracts up to ~2 degrees (~220km variance)
     */
    private function generateLatitude(float $baseLat): float
    {
        return round($baseLat + (fake()->randomFloat(4, -2, 2)), 7);
    }

    /**
     * Generate a random longitude near the base coordinate
     * Adds/subtracts up to ~2 degrees (~220km variance)
     */
    private function generateLongitude(float $baseLng): float
    {
        return round($baseLng + (fake()->randomFloat(4, -2, 2)), 7);
    }
}
