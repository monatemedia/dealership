<?php // database/seeders/DriveTrainSeeder.php

namespace Database\Seeders;

use App\Models\DriveTrain;
use App\Models\DriveTrainGroup;
use Illuminate\Database\Seeder;

class DriveTrainSeeder extends Seeder
{
    public function run(): void
    {
        // Note: config key is 'drive_train' not 'drive_trains'
        $config = config('lookups.drive_train');

        foreach ($config as $groupName => $driveTrains) {
            $group = DriveTrainGroup::updateOrCreate(
                ['name' => $groupName]
            );

            foreach ($driveTrains as $driveTrainName) {
                DriveTrain::updateOrCreate(
                    [
                        'name' => $driveTrainName,
                        'drive_train_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
