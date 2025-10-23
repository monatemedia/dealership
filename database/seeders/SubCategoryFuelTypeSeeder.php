<?php // database/seeders/SubCategoryFuelTypeSeeder.php

namespace Database\Seeders;

use App\Models\FuelTypeGroup;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategoryFuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.fuel_types_by_category');

        foreach ($config as $subCategoryName => $fuelConfig) {
            $subCategory = SubCategory::where('name', $subCategoryName)->first();

            if (!$subCategory) {
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
                $subCategory->fuelTypeGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_fuel_type' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
