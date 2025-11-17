<?php

// app/Console/Commands/ImportToTypesense.php

namespace App\Console\Commands;

use App\Models\Vehicle;
use Illuminate\Console\Command;

class ImportToTypesense extends Command
{
    protected $signature = 'typesense:import
        {--model= : Specific model to import}
        {--force : Force a fresh import (drop and create collection)}';

    protected $description = 'Import data to Typesense with correct IDs and improved performance.';

    // Updated to include Vehicle model
    protected $modelsToImport = [
        'App\Models\Manufacturer',
        'App\Models\Model',
        'App\Models\Province',
        'App\Models\City',
        'App\Models\Vehicle',  // Added Vehicle model
    ];

    // Chunk size for bulk operations
    protected const CHUNK_SIZE = 500; // Reduced from 2000 for better memory management with relationships

    public function handle()
    {
        $models = $this->option('model')
            ? [$this->option('model')]
            : $this->modelsToImport;

        $totalStartTime = microtime(true);

        foreach ($models as $modelClass) {
            // Verify model exists and uses Searchable trait
            if (! class_exists($modelClass)) {
                $this->error("Model {$modelClass} does not exist.");

                continue;
            }

            if (! in_array(\Laravel\Scout\Searchable::class, class_uses($modelClass))) {
                $this->error("Model {$modelClass} does not use the Laravel Scout Searchable trait.");

                continue;
            }

            $this->importModel($modelClass);
        }

        $totalTime = round(microtime(true) - $totalStartTime, 2);

        $this->newLine();
        $this->info("✨ Import completed in {$totalTime} seconds");
        $this->info('🔍 Your data is now searchable with InstantSearch!');

        return 0;
    }

    protected function importModel(string $modelClass): void
    {
        $modelName = class_basename($modelClass);
        $this->info("Importing {$modelName}...");

        $startTime = microtime(true);
        $count = 0;

        // Start building the query
        $query = $modelClass::query();

        // Special handling for Vehicle model - eager load relationships
        if ($modelClass === Vehicle::class) {
            $this->info('  Loading relationships for denormalized search data...');

            $query->with([
                'manufacturer',
                'model',
                'vehicleType',
                'fuelType',
                'city.province',
                'primaryImage',
            ]);
        }

        // Get total count for progress display
        $total = $query->count();

        if ($total === 0) {
            $this->warn("  ⚠ No {$modelName} records found to import");

            return;
        }

        $this->info("  Found {$total} {$modelName} records to import");

        // Progress bar
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        // Chunk the query for memory efficiency
        $query->chunk(self::CHUNK_SIZE, function ($records) use (&$count, $bar) {
            // Make searchable using Scout's bulk indexing
            $records->searchable();

            $count += $records->count();
            $bar->advance($records->count());
        });

        $bar->finish();
        $this->newLine();

        $duration = round(microtime(true) - $startTime, 2);
        $this->info("✅ {$modelName}: {$count} records imported in {$duration}s");

        // Show some helpful stats
        if ($modelClass === Vehicle::class && $count > 0) {
            $this->displayVehicleStats();
        }
    }

    protected function displayVehicleStats(): void
    {
        try {
            $stats = [
                'Total Vehicles' => Vehicle::count(),
                'With Images' => Vehicle::whereHas('primaryImage')->count(),
                'Active Listings' => Vehicle::where('status', 'active')->count(),
            ];

            $this->newLine();
            $this->info('📊 Vehicle Statistics:');
            foreach ($stats as $label => $value) {
                $this->info("  {$label}: {$value}");
            }
        } catch (\Exception $e) {
            // Silently fail if stats can't be retrieved
        }
    }
}
