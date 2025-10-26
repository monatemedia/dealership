<?php // database/factories/VehicleFactory.php

namespace Database\Factories;

use App\Models\AccidentHistory;
use App\Models\Color;
use App\Models\Drivetrain;
use App\Models\Interior;
use App\Models\MainCategory;
use App\Models\Subcategory;
use App\Models\Transmission;
use App\Models\VehicleType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Manufacturer;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Fetch a random user first to ensure we get a phone number
        $user = User::inRandomOrder()->first();

        // Fetch a random manufacturer
        $manufacturer = Manufacturer::inRandomOrder()->first();
        // Pick a random main category
        $mainCategory = MainCategory::inRandomOrder()->first();

        // Pick a random sub-category belonging to the main category (nullable)
        $subcategory = Subcategory::where('main_category_id', $mainCategory->id)
            ->inRandomOrder()
            ->first();

        // Pick a random vehicle type for this sub-category
        $vehicleType = VehicleType::where('subcategory_id', $subcategory->id)
            ->inRandomOrder()
            ->first();

        return [
            'main_category_id' => $mainCategory->id,
            'subcategory_id' => $subcategory?->id, // nullable if no sub-category exists

            // Manufacturer
            'manufacturer_id' => $manufacturer->id, // Assign the manufacturer ID

            // Model
            'model_id' => function (array $attributes) {
                // Get random model based on manufacturer id
                return Model::where('manufacturer_id', $attributes['manufacturer_id'])
                    ->inRandomOrder() // Get random model
                    ->first() // Get first model
                    ->id; // Get model id
            },

            // Year
            'year' => fake()->year(),

            // Price in range 5 000 - 100 000
            'price' => ((int) fake() // Convert to integer
                ->randomFloat(2, 5, 100)) // Random float in range 5 - 100
                * 1000, // Multiply by 1000

            // Generate random vin number
            'vin' => strtoupper(Str::random(17)),

            // Milage in range 5 000 - 500 000
            'mileage' => ((int) // Convert to integer
                fake() // Fake data
                    ->randomFloat(2, 5, 500)) // Random float in range 5 - 500
                * 1000, // Multiply by 1000

            // Vehicle type
            'vehicle_type_id' => $vehicleType?->id,

            // Fuel type
            'fuel_type_id' => FuelType::inRandomOrder() // Get random fuel type
                ->first() // Get first fuel type
                ->id, // Get fuel type id

            // Transmission
            'transmission_id' => Transmission::inRandomOrder()->first()?->id,

            // Drivetrain
            'drivetrain_id' => Drivetrain::inRandomOrder()->first()?->id,

            // Color - NEW
            'color_id' => Color::inRandomOrder()
                ->first()
                ?->id,

            // Interior - NEW
            'interior_id' => Interior::inRandomOrder()
                ->first()
                ?->id,

            // Accident History - NEW
            'accident_history_id' => AccidentHistory::inRandomOrder()
                ->first()
                ?->id,

            // User
            'user_id' => $user->id,  // Assign the user ID

            // City
            'city_id' => City::inRandomOrder() // Get random city
                ->first() // Get first city
                ->id, // Get city id

            // Address
            'address' => fake()->address, // Random address

            // Phone number
            'phone' => $user->phone,  // Assign the user's phone number directly

            // Description
            'description' => fake()
                ->text(2000), // Random text with max length 2000

            // Published at date
            'published_at' => fake()
                ->optional(0.9) // 90% chance the value will be generated
                ->dateTimeBetween('-1 month', '+1 day') // A date in the range between the past month or the next day
        ];
    }
}
