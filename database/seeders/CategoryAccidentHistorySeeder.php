<?php
// database/seeders/CategoryAccidentHistorySeeder.php

namespace Database\Seeders;

use App\Models\AccidentHistoryGroup;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryAccidentHistorySeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.accident_history_by_category');

        foreach ($config as $categoryName => $accidentConfig) {
            $category = Category::where('name', $categoryName)->first();

            if (!$category) {
                continue;
            }

            $groups = $accidentConfig['groups'] ?? [];
            $default = $accidentConfig['default'] ?? null;
            $canEdit = $accidentConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = AccidentHistoryGroup::where('name', $groupName)->first();

                if (!$group) {
                    continue;
                }

                // Attach the group to the category with pivot data
                $category->accidentHistoryGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_accident_history' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
