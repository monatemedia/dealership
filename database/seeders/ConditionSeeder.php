<?php
// database/seeders/ConditionSeeder.php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ConditionSeeder extends Seeder
{
    public function run(): void
    {
        $conditions = config('lookups.condition'); // They're all the same

        foreach ($conditions as $index => $name) {
            Condition::firstOrCreate([
                'name' => $name,
                'slug' => Str::slug($name),
                'order' => $index + 1,
            ]);
        }
    }
}
