<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Manufacturer;
use App\Models\Model;
use App\Models\Province;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create car types
        CarType::factory()
            // Create 9 car types
            ->sequence( // Define the sequence of car types
                ['name' => 'Sedan'],
                ['name' => 'Hatchback'],
                ['name' => 'SUV'],
                ['name' => 'Pickup Truck'],
                ['name' => 'Minivan'],
                ['name' => 'Jeep'],
                ['name' => 'Coupe'],
                ['name' => 'Crossover'],
                ['name' => 'Sports Car'],
                ['name' => 'Sedan'],
            )
            ->count(9) // Count must be the same as the number of car types in the sequence
            ->create(); // Create the car types

        // Create fuel types
        FuelType::factory()
            ->count(4) // Create 4 fuel types
            ->sequence( // Define the sequence of fuel types
                ['name' => 'Gasoline'],
                ['name' => 'Diesel'],
                ['name' => 'Electric'],
                ['name' => 'Hybrid']
            )
            ->create();

        // Province and City data
        $provinces = [
            'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'San Jose', 'Sacramento'],
            'Texas' => ['Houston', 'San Antonio', 'Dallas', 'Austin', 'Fort Worth'],
            'Florida' => ['Miami', 'Orlando', 'Tampa', 'Jacksonville', 'St. Petersburg'],
            'New York' => ['New York City', 'Buffalo', 'Rochester', 'Yonkers', 'Syracuse'],
            'Illinois' => ['Chicago', 'Aurora', 'Naperville', 'Joliet', 'Rockford'],
            'Pennsylvania' => ['Philadelphia', 'Pittsburgh', 'Allentown', 'Erie', 'Reading'],
            'Ohio' => ['Columbus', 'Cleveland', 'Cincinnati', 'Toledo', 'Akron'],
            'Georgia' => ['Atlanta', 'Augusta', 'Columbus', 'Savannah', 'Athens'],
            'North Carolina' => ['Charlotte', 'Raleigh', 'Greensboro', 'Durham', 'Winston-Salem'],
            'Michigan' => ['Detroit', 'Grand Rapids', 'Warren', 'Sterling Heights', 'Ann Arbor'],
        ];

        // Create Provinces with Cities
        foreach ($provinces as $province => $cities) {
            Province::factory() // Create a province
                ->state(['name' => $province]) // Set the name of the province
                ->has( // Create cities for the province
                    City::factory() // Create a city
                        ->count(count($cities)) // Create as many cities as the number of cities in the array
                        // Add ... to desctructure the array and pass each city as a separate argument
                        ->sequence(...array_map(fn($city)
                            => ['name' => $city], $cities)) // Set the name of the city
                )
                ->create(); // Create the province
        }

        // manufacturers and models data
        $manufacturers = [
            'Toyota' => ['Camry', 'Corolla', 'Highlander', 'RAV4', 'Prius', '4Runner', 'Sienna', 'Yaris', 'Tundra', 'Sequoia'],
            'Ford' => ['F-150', 'Escape', 'Explorer', 'Mustang', 'Fusion', 'Ranger', 'Edge', 'Expedition', 'Taurus', 'Flex'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Odyssey', 'HR-V', 'Ridgeline', 'Fit', 'Insight', 'Passport'],
            'Chevrolet' => ['Silverado', 'Equinox', 'Malibu', 'Impala', 'Cruze', 'Colorado', 'Camaro', 'Traverse', 'Tahoe', 'Suburban'],
            'Nissan' => ['Altima', 'Sentra', 'Rogue', 'Maxima', 'Murano', 'Pathfinder', 'Frontier', 'Titan', 'Versa', '370Z'],
            'Lexus' => ['RX400', 'RX450', 'RX350', 'ES350', 'LS500', 'IS300', 'GX460', 'GS350', 'NX300', 'LX570', 'UX200', 'RC350']
        ];

        // Create manufacturers with models
        foreach ($manufacturers as $manufacturer => $models) { // Loop through the manufacturers
            Manufacturer::factory() // Create a manufacturer
                ->state(['name' => $manufacturer]) // Set the name of the manufacturer
                ->has( // Create models for the manufacturer
                    Model::factory() // Create a model
                        ->count(count($models)) // Create as many models as the number of models in the array
                        ->sequence( // Define the sequence of models
                            // Add ... to desctructure the array and pass each model as a separate argument
                            // Use array_map to create an array of arrays with the name key
                            ...array_map(fn($model)
                                => ['name' => $model], $models) // Set the name of the model
                        )
                )
                ->create(); // Create the manufacturer
        }

        // Create users, cars with images and features
        // Create 3 users first, then creae 2 more users,
        // and for each user (from the last 2 users) create 50 cars,
        // with images and features and add these cars to favourite cars of these two users

        // Create 3 users
        User::factory() // Create a user
            ->count(3) // Create 3 users
            ->create();

        // Create 2 more users and 50 new cars, each added to their favouriteCars.
        // Each Car will have 5 images
        User::factory()
            ->count(2) // Create 2 users
            ->has( // Create 50 cars for each user
                Car::factory()
                    ->count(50) // Create 50 cars
                    ->has( // Create 5 images for each car
                        CarImage::factory()
                            ->count(5) // Create 5 images
                            ->sequence(fn(Sequence $sequence)
                                => [
                                    'position'
                                    => ($sequence->index) % 5 + 1 // Set the position of the image
                                ]),
                        // We can also use the following code to set the position of the image
                        // ->sequence(
                        //     ['position' => 1],
                        //     ['position' => 2],
                        //     ['position' => 3],
                        //     ['position' => 4],
                        //     ['position' => 5]
                        // ),
                        'images'
                    )
                    ->hasFeatures(), // Add features to the car
                'favouriteCars'
            )
            ->create(); // Create the users
    }
}
