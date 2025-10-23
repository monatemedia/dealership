<?php // database/seeders/FuelTypeSeeder.php

namespace Database\Seeders;

use App\Models\FuelType;
use App\Models\FuelTypeGroup;
use Illuminate\Database\Seeder;

class FuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fuelTypesConfig = config('lookups.fuel_types');

        foreach ($fuelTypesConfig as $groupName => $fuelTypes) {
            // Create the fuel type group
            $group = FuelTypeGroup::updateOrCreate(
                ['name' => $groupName]
            );

            // Create fuel types for this group
            foreach ($fuelTypes as $fuelTypeName) {
                FuelType::updateOrCreate(
                    [
                        'name' => $fuelTypeName,
                        'fuel_type_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
