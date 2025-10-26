<?php
// database/seeders/SubcategoryInteriorSeeder.php

namespace Database\Seeders;

use App\Models\InteriorGroup;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategoryInteriorSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.interiors_by_subcategory');

        foreach ($config as $subcategoryName => $interiorConfig) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();

            if (!$subcategory) {
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

                // Attach the group to the sub-category with pivot data
                $subcategory->interiorGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_interior' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
