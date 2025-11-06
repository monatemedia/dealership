<?php // database/seeders/SubcategoryFuelTypeSeeder.php

namespace Database\Seeders;

use App\Models\FuelTypeGroup;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategoryFuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.fuel_types_by_subcategory');

        foreach ($config as $subcategoryName => $fuelConfig) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();

            if (!$subcategory) {
                continue;
            }

            $groups = $fuelConfig['groups'] ?? [];
            $default = $fuelConfig['default'] ?? null;
            $canEdit = $fuelConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = FuelTypeGroup::where('name', $groupName)->first();

                if (!$group) {
                    continue;
                }

                // Attach the group to the sub-category with pivot data
                $subcategory->fuelTypeGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_fuel_type' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
