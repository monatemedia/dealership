<?php
// database/seeders/SubcategoryAccidentHistorySeeder.php

namespace Database\Seeders;

use App\Models\AccidentHistoryGroup;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategoryAccidentHistorySeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.accident_history_by_subcategory');

        foreach ($config as $subcategoryName => $accidentConfig) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();

            if (!$subcategory) {
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

                // Attach the group to the sub-category with pivot data
                $subcategory->accidentHistoryGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_accident_history' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
