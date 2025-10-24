<?php // database/seeders/SubCategoryTransmissionSeeder.php

namespace Database\Seeders;

use App\Models\SubCategory;
use App\Models\TransmissionGroup;
use Illuminate\Database\Seeder;

class SubCategoryTransmissionSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.transmissions_by_category');

        foreach ($config as $subCategoryName => $transmissionConfig) {
            $subCategory = SubCategory::where('name', $subCategoryName)->first();
            if (!$subCategory) {
                continue;
            }

            $groups = $transmissionConfig['groups'] ?? [];
            $default = $transmissionConfig['default'] ?? null;
            $canEdit = $transmissionConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = TransmissionGroup::where('name', $groupName)->first();
                if (!$group) {
                    continue;
                }

                $subCategory->transmissionGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_transmission' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
