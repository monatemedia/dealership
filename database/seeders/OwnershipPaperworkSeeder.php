<?php // database/seeders/OwnershipPaperworkSeeder.php

namespace Database\Seeders;

use App\Models\OwnershipPaperwork;
use Illuminate\Database\Seeder;

class OwnershipPaperworkSeeder extends Seeder
{
    public function run(): void
    {
        $paperwork = config('ownership_paperwork.ownership_paperwork');

        foreach ($paperwork as $category => $items) {
            if ($category === 'None') continue; // Skip 'None' category

            foreach ($items as $name) {
                OwnershipPaperwork::firstOrCreate([
                    'name' => $name,
                    'category' => $category
                ]);
            }
        }
    }
}
