<?php // database/seeders/ProductionManufacturerSeeder.php
namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("Seeding manufacturers and models from VPIC SQLite database...");

        // Step 1: Import manufacturers using upsert for speed
        $this->command->info("Importing manufacturers...");

        $manufacturers = DB::connection('vpic')
            ->table('Make_Model AS mm')
            ->join('Make AS m', 'mm.MakeId', '=', 'm.Id')
            ->select('m.Name as name')
            ->distinct()
            ->get()
            ->map(fn($m) => [
                'name' => $m->name,
                'created_at' => now(),
                'updated_at' => now(),
            ])
            ->all();

        $totalManufacturers = count($manufacturers);

        // Use upsert for idempotent bulk insert
        // This will insert new records and update existing ones
        DB::table('manufacturers')->upsert(
            $manufacturers,
            ['name'], // Unique key
            ['updated_at'] // Fields to update if exists
        );

        $this->command->info("Processed {$totalManufacturers} manufacturers.");

        unset($manufacturers);
        gc_collect_cycles();

        // Step 2: Build manufacturer lookup map
        $this->command->info("Importing models...");
        $manufacturerMap = Manufacturer::pluck('id', 'name')->all();

        // Get total count for progress tracking
        $totalModels = DB::connection('vpic')
            ->table('Make_Model AS mm')
            ->count();

        $processedModels = 0;
        $chunkSize = 2000; // Larger chunks for better performance

        // Step 3: Import models in chunks using upsert
        DB::connection('vpic')
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
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Use upsert for idempotent operation
                if (!empty($modelsToUpsert)) {
                    DB::table('models')->upsert(
                        $modelsToUpsert,
                        ['name', 'manufacturer_id'], // Composite unique key
                        ['updated_at'] // Update timestamp if exists
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
