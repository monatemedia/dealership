<?php // database/factories/TransmissionFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TransmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['5-Speed Manual', '6-Speed Automatic', 'CVT']),
        ];
    }
}
