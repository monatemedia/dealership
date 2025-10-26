<?php
// database/seeders/SubcategoryColorSeeder.php

namespace Database\Seeders;

use App\Models\ColorGroup;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategoryColorSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.colors_by_subcategory');

        foreach ($config as $subcategoryName => $colorConfig) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();

            if (!$subcategory) {
                continue;
            }

            $groups = $colorConfig['groups'] ?? [];
            $default = $colorConfig['default'] ?? null;
            $canEdit = $colorConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = ColorGroup::where('name', $groupName)->first();

                if (!$group) {
                    continue;
                }

                // Attach the group to the sub-category with pivot data
                $subcategory->colorGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_color' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
