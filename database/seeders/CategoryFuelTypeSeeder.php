<?php // database/seeders/CategoryFuelTypeSeeder.php

namespace Database\Seeders;

use App\Models\FuelTypeGroup;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryFuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.fuel_types_by_category');

        foreach ($config as $categoryName => $fuelConfig) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
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

                // Attach the group to the category with pivot data
                $category->fuelTypeGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_fuel_type' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
