<?php
// database/seeders/CategoryInteriorSeeder.php

namespace Database\Seeders;

use App\Models\InteriorGroup;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryInteriorSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.interiors_by_category');

        foreach ($config as $categoryName => $interiorConfig) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                continue;
            }

            $groups = $interiorConfig['groups'] ?? [];
            $default = $interiorConfig['default'] ?? null;
            $canEdit = $interiorConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = InteriorGroup::where('name', $groupName)->first();

                if (!$group) {
                    continue;
                }

                // Attach the group to the category with pivot data
                $category->interiorGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_interior' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
