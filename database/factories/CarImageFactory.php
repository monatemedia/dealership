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
            // Generate random image URL with colors and text
            'image_path' => 'https://placehold.co/'
                . fake()->numberBetween(300, 800) . 'x' // Width random between 300 and 800
                . fake()->numberBetween(200, 600) . '/' // Height random between 200 and 600
                . fake()->safeColorName() . '/' // Background color
                . fake()->safeColorName() . // Text color
                '.png?text='
                . fake()->word(), // Random text for the image

            // Position
            'position' => function (array $attributes) {
                return Car::find($attributes['car_id']) // Find the car
                    ->images() // Get the images
                    ->count() + 1; // Increment the count
            }
        ];
    }
}
