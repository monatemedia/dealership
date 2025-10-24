<?php // database/factories/DriveTrainFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DriveTrain>
 */
class DriveTrainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Front-Wheel Drive (FWD)', 'All-Wheel Drive (AWD)']),
        ];
    }
}
