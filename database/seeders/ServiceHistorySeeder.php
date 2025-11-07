<?php
// database/seeders/ServiceHistorySeeder.php

namespace Database\Seeders;

use App\Models\ServiceHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceHistorySeeder extends Seeder
{
    public function run(): void
    {
        $histories = config('lookups.service_history');

        foreach ($histories as $index => $name) {
            ServiceHistory::firstOrCreate([
                'name' => $name,
                'slug' => Str::slug($name),
                'order' => $index + 1,
            ]);
        }
    }
}
