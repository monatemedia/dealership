<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleCategory>
 */
class VehicleCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     * VehicleCategory::class
     * @var string
     */

    protected $model = VehicleCategory::class;

    public function definition(): array
    {
        $categories = config('vehicles.categories');

        // Pick a random category from config
        $name = $this->faker->randomElement(array_keys($categories));

        return [
            'name' => $name,
            'singular' => $categories[$name]['singular'],
            'long_name' => $categories[$name]['long_name'],
            'description' => $categories[$name]['description'],
            'image_path' => $categories[$name]['image_path'],
            'slug' => Str::slug($name),
            // 'vehicle_types' => $categories[$name]['vehicle_types'],
            // 'fuel_types' => $categories[$name]['fuel_types'],
        ];
    }
}
