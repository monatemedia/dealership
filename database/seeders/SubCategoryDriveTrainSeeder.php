<?php // database/seeders/SubCategoryDriveTrainSeeder.php

namespace Database\Seeders;

use App\Models\SubCategory;
use App\Models\DriveTrainGroup;
use Illuminate\Database\Seeder;

class SubCategoryDriveTrainSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('categories.drive_train_by_category');

        foreach ($config as $subCategoryName => $driveTrainConfig) {
            $subCategory = SubCategory::where('name', $subCategoryName)->first();
            if (!$subCategory) {
                continue;
            }

            $groups = $driveTrainConfig['groups'] ?? [];
            $default = $driveTrainConfig['default'] ?? null;
            $canEdit = $driveTrainConfig['can_edit'] ?? true;

            foreach ($groups as $groupName) {
                $group = DriveTrainGroup::where('name', $groupName)->first();
                if (!$group) {
                    continue;
                }

                $subCategory->driveTrainGroups()->syncWithoutDetaching([
                    $group->id => [
                        'default_drive_train' => $default,
                        'can_edit' => $canEdit
                    ]
                ]);
            }
        }
    }
}
