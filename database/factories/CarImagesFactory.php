<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarImage>
 */
class CarImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'car_id' => 1, // Prove a car id

            // Image URL
            'image_path' => fake()
                ->imageUrl(), // Generate a random image URL

            // Position
            'position' => function (array $attributes) {
                return Car::find($attributes['car_id']) // Find the car
                    ->images() // Get the images
                    ->count() + 1; // Increment the count
            }
        ];
    }
}
