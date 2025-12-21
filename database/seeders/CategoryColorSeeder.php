<?php
// database/seeders/CategoryColorSeeder.php

namespace Database\Seeders;

use App\Models\ColorGroup;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryColorSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.colors_by_category');

        foreach ($config as $categoryName => $colorConfig) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
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

                // Attach the group to the category with pivot data
                $category->colorGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_color' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
