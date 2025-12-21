<?php // database/seeders/CategoryTransmissionSeeder.php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\TransmissionGroup;
use Illuminate\Database\Seeder;

class CategoryTransmissionSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.transmissions_by_category');

        foreach ($config as $categoryName => $transmissionConfig) {
            $category = Category::where('name', $categoryName)->first();
            if (!$category) {
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

                $category->transmissionGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_transmission' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
