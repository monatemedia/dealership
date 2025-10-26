<?php // database/seeders/SubcategoryDrivetrainSeeder.php

namespace Database\Seeders;

use App\Models\Subcategory;
use App\Models\DrivetrainGroup;
use Illuminate\Database\Seeder;

class SubcategoryDrivetrainSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.drivetrain_by_category');

        foreach ($config as $subcategoryName => $driveTrainConfig) {
            $subcategory = Subcategory::where('name', $subcategoryName)->first();
            if (!$subcategory) {
                continue;
            }

            $groups = $driveTrainConfig['groups'] ?? [];
            $default = $driveTrainConfig['default'] ?? null;
            $canEdit = $driveTrainConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = DrivetrainGroup::where('name', $groupName)->first();
                if (!$group) {
                    continue;
                }

                $subcategory->driveTrainGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_drivetrain' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
