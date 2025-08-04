<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarImage>
 */
class CarImageFactory extends Factory
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
                $car = Car::find($attributes['car_id']);

                return sprintf(
                    'https://placehold.co/%dx%d/%s/%s/png?text=%s',
                    fake()->numberBetween(300, 800),  // width
                    fake()->numberBetween(200, 600),  // height
                    fake()->safeColorName(),          // background color
                    fake()->safeColorName(),          // text color
                    urlencode($car->manufacturer->name) // actual text
                );
            },

            'position' => function (array $attributes) {
                return Car::find($attributes['car_id'])
                    ->images()
                    ->count() + 1;
            }
        ];
    }
}
