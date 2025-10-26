<?php // database/seeders/SubcategoryTransmissionSeeder.php

namespace Database\Seeders;

use App\Models\Subcategory;
use App\Models\TransmissionGroup;
use Illuminate\Database\Seeder;

class SubcategoryTransmissionSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.transmissions_by_category');

        foreach ($config as $subcategoryName => $transmissionConfig) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();
            if (!$subcategory) {
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

                $subcategory->transmissionGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_transmission' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
