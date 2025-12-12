<?php // database/seeders/ProductionManufacturerSeeder.php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Seeding manufacturers and models from make_model SQLite database...");

        // Step 1: Import manufacturers using upsert for speed
        $this->command->info("Importing manufacturers...");
        $manufacturers = DB::connection('make_model')
            ->table('Make_Model AS mm')
            ->join('Make AS m', 'mm.MakeId', '=', 'm.Id')
            ->select('m.Name as name')
            ->distinct()
            ->get()
            ->map(fn($m) => [
                'name' => $m->name,
                // Timestamps removed as per model definition
            ])
            ->all();

        $totalManufacturers = count($manufacturers);

        // Use upsert for idempotent bulk insert. Using 'name' in the update array
        // forces PostgreSQL to use ON CONFLICT DO UPDATE SET name = EXCLUDED.name.
        DB::table('manufacturers')->upsert(
            $manufacturers,
            ['name'], // Unique key
            ['name'] // Update an existing field to itself if conflict occurs
        );

        $this->command->info("Processed {$totalManufacturers} manufacturers.");
        unset($manufacturers);
        gc_collect_cycles();

        // Step 2: Build manufacturer lookup map
        $this->command->info("Importing models...");
        $manufacturerMap = Manufacturer::pluck('id', 'name')->all();

        // Get total count for progress tracking
        $totalModels = DB::connection('make_model')
            ->table('Make_Model AS mm')
            ->count();
        $processedModels = 0;
        $chunkSize = 2000; // Larger chunks for better performance

        // Step 3: Import models in chunks using upsert
        DB::connection('make_model')
            ->table('Make_Model AS mm')
            ->join('Make AS m', 'mm.MakeId', '=', 'm.Id')
            ->join('Model AS mo', 'mm.ModelId', '=', 'mo.Id')
            ->select('m.Name as make_name', 'mo.Name as model_name')
            ->orderBy('mm.Id')
            ->chunk($chunkSize, function ($rows) use ($manufacturerMap, &$processedModels, $totalModels) {
                $modelsToUpsert = [];
                foreach ($rows as $row) {
                    $manufacturerId = $manufacturerMap[$row->make_name] ?? null;
                    if ($manufacturerId) {
                        $modelsToUpsert[] = [
                            'name' => $row->model_name,
                            'manufacturer_id' => $manufacturerId,
                            // Timestamps removed as per model definition
                        ];
                    }
                }

                // Use upsert for idempotent operation. Using 'name' in the update array
                // forces PostgreSQL to use ON CONFLICT DO UPDATE SET name = EXCLUDED.name.
                if (!empty($modelsToUpsert)) {
                    DB::table('models')->upsert(
                        $modelsToUpsert,
                        ['name', 'manufacturer_id'], // Composite unique key
                        ['name'] // Update an existing field to itself if conflict occurs
                    );
                }

                $processedModels += count($rows);

                // Show progress less frequently for speed
                if ($processedModels % 5000 === 0 || $processedModels >= $totalModels) {
                    $this->command->info("Processed {$processedModels}/{$totalModels} models...");
                }
                unset($modelsToUpsert);
                gc_collect_cycles();
            });

        $this->command->info("Finished seeding manufacturers and models.");
    }
}
