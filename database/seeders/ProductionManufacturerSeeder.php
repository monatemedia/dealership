<?php // database/seeders/ProductionManufacturerSeeder.php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Enums\DataSource;
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
                'source' => DataSource::ORIGINAL->value,
            ])
            ->all();

        // Use upsert for idempotent bulk insert. Using 'name' in the update array
        // forces PostgreSQL to use ON CONFLICT DO UPDATE SET name = EXCLUDED.name.
        DB::table('manufacturers')->upsert(
            $manufacturers,
            ['name'],
            ['source']
        );

        // --- NEW: Populate Manufacturer Aliases for instant lookup ---
        $this->command->info("Creating manufacturer aliases...");
        $allManufacturers = Manufacturer::all();
        $mAliases = $allManufacturers->map(fn($m) => [
            'alias' => strtolower(trim($m->name)),
            'manufacturer_id' => $m->id
        ])->all();
        DB::table('manufacturer_aliases')->upsert($mAliases, ['alias'], ['manufacturer_id']);
        // ------------------------------------------------------------

        unset($manufacturers, $mAliases);
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
                            'source' => DataSource::ORIGINAL->value,
                        ];
                    }
                }

                // Use upsert for idempotent operation. Using 'name' in the update array
                // forces PostgreSQL to use ON CONFLICT DO UPDATE SET name = EXCLUDED.name.
                if (!empty($modelsToUpsert)) {
                    // 1. Upsert Models
                    DB::table('models')->upsert(
                        $modelsToUpsert,
                        ['name', 'manufacturer_id'],
                        ['source']
                    );

                    // 2. Fetch the IDs of the models we just inserted to create aliases
                    // (This is slightly slower but ensures the alias table is primed)
                    $insertedModels = \App\Models\Model::whereIn('name', array_column($modelsToUpsert, 'name'))
                        ->whereIn('manufacturer_id', array_column($modelsToUpsert, 'manufacturer_id'))
                        ->get();

                    foreach ($insertedModels as $im) {
                        $modelAliases[] = [
                            'alias' => strtolower(trim($im->name)),
                            'model_id' => $im->id
                        ];
                    }

                    DB::table('model_aliases')->upsert($modelAliases, ['alias', 'model_id'], ['model_id']);
                }

                $processedModels += count($rows);

                // Show progress less frequently for speed
                if ($processedModels % 5000 === 0 || $processedModels >= $totalModels) {
                    $this->command->info("Processed {$processedModels}/{$totalModels} models...");
                }
            });

        $this->command->info("Finished seeding manufacturers and models.");
    }
}
