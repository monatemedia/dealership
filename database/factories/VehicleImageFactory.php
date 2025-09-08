<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleImage>
 */
class VehicleImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image_path' => function (array $attributes) {
                $vehicle = Vehicle::find($attributes['vehicle_id']);

                return sprintf(
                    'https://placehold.co/%dx%d/%s/%s/png?text=%s',
                    fake()->numberBetween(300, 800),  // width
                    fake()->numberBetween(200, 600),  // height
                    fake()->safeColorName(),          // background color
                    fake()->safeColorName(),          // text color
                    urlencode($vehicle->manufacturer->name) // actual text
                );
            },

            'position' => function (array $attributes) {
                return Vehicle::find($attributes['vehicle_id'])
                    ->images()
                    ->count() + 1;
            }
        ];
    }
}
