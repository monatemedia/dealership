<?php // database/seeders/TransmissionSeeder.php

namespace Database\Seeders;

use App\Models\Transmission;
use App\Models\TransmissionGroup;
use Illuminate\Database\Seeder;

class TransmissionSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('lookups.transmissions');

        foreach ($config as $groupName => $transmissions) {
            $group = TransmissionGroup::updateOrCreate(
                ['name' => $groupName]
            );

            foreach ($transmissions as $transmissionName) {
                Transmission::updateOrCreate(
                    [
                        'name' => $transmissionName,
                        'transmission_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
