<?php // database/seeders/SubcategoryFeatureSeeder.php

namespace Database\Seeders;

use App\Models\FeatureGroup;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategoryFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.features_by_subcategory');

        foreach ($config as $subcategoryName => $settings) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();

            if (!$subcategory) {
                $this->command->warn("Subcategory not found: {$subcategoryName}");
                continue;
            }

            foreach ($settings['groups'] as $groupName) {
                if ($groupName === 'None') continue;

                $group = FeatureGroup::where('name', $groupName)->first();

                if (!$group) {
                    $this->command->warn("Feature group not found: {$groupName}");
                    continue;
                }

                $subcategory->featureGroups()->syncWithoutDetaching([
                    $group->id => [
                        'can_edit' => $settings['can_edit']
                    ]
                ]);
            }

            $this->command->info("Linked features for: {$subcategoryName}");
        }
    }
}
