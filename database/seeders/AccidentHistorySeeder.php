<?php
// database/seeders/AccidentHistorySeeder.php

namespace Database\Seeders;

use App\Models\AccidentHistory;
use App\Models\AccidentHistoryGroup;
use Illuminate\Database\Seeder;

class AccidentHistorySeeder extends Seeder
{
    public function run(): void
    {
        $accidentHistoriesConfig = config('lookups.accident_history');

        foreach ($accidentHistoriesConfig as $groupName => $histories) {
            // Create the accident history group
            $group = AccidentHistoryGroup::updateOrCreate(
                ['name' => $groupName]
            );

            // Create accident histories for this group
            foreach ($histories as $historyName) {
                AccidentHistory::updateOrCreate(
                    [
                        'name' => $historyName,
                        'accident_history_group_id' => $group->id
                    ]
                );
            }
        }
    }
}
