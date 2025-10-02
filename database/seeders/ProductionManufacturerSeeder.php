<?php // database/seeders/ProductionManufacturerSeeder.php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("    Seeding manufacturers and models from VPIC SQLite database...");

        // Step 1: Import all unique manufacturers first
        $this->command->info("    Importing manufacturers...");

        $manufacturers = DB::connection('vpic')
            ->table('Make_Model AS mm')
            ->join('Make AS m', 'mm.MakeId', '=', 'm.Id')
            ->select('m.Id as vpic_id', 'm.Name as name')
            ->distinct()
            ->get();

        $totalManufacturers = $manufacturers->count();
        $processedManufacturers = 0;

        foreach ($manufacturers as $make) {
            Manufacturer::firstOrCreate(
                ['name' => $make->name]
            );

            $processedManufacturers++;

            // Show progress every 500 manufacturers
            if ($processedManufacturers % 500 === 0 || $processedManufacturers === $totalManufacturers) {
                $this->command->info("      Processed {$processedManufacturers}/{$totalManufacturers} manufacturers...");
            }
        }

        // Clear any cached data
        unset($manufacturers);
        gc_collect_cycles();

        $this->command->info("    Importing models...");

        // Step 2: Build a manufacturer ID lookup map to avoid repeated queries
        $manufacturerMap = Manufacturer::pluck('id', 'name')->all();

        // Get total count for progress tracking
        $totalModels = DB::connection('vpic')
            ->table('Make_Model AS mm')
            ->count();

        $processedModels = 0;

        // Step 3: Import models in chunks
        DB::connection('vpic')
            ->table('Make_Model AS mm')
            ->join('Make AS m', 'mm.MakeId', '=', 'm.Id')
            ->join('Model AS mo', 'mm.ModelId', '=', 'mo.Id')
            ->select('m.Name as make_name', 'mo.Name as model_name', 'mm.Id as chunk_id')
            ->orderBy('mm.Id')
            ->chunk(1000, function ($rows) use ($manufacturerMap, &$processedModels, $totalModels) {
                $modelsToInsert = [];

                foreach ($rows as $row) {
                    $manufacturerId = $manufacturerMap[$row->make_name] ?? null;

                    if ($manufacturerId) {
                        $modelsToInsert[] = [
                            'name' => $row->model_name,
                            'manufacturer_id' => $manufacturerId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Use insertOrIgnore to skip duplicates (Laravel 8+)
                if (!empty($modelsToInsert)) {
                    DB::table('models')->insertOrIgnore($modelsToInsert);
                }

                $processedModels += count($rows);
                $this->command->info("      Processed {$processedModels}/{$totalModels} models...");

                // Force garbage collection
                unset($modelsToInsert);
                gc_collect_cycles();
            });

        $this->command->info("      Finished seeding manufacturers and models.");
    }
}
