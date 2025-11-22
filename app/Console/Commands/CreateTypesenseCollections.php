<?php
// app/Console/Commands/CreateTypesenseCollections.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client as TypesenseClient;

class CreateTypesenseCollections extends Command
{
    protected $signature = 'typesense:create-collections
        {--force : Delete existing collections}
        {--import : Automatically import data after creating collections}';

    protected $description = 'Create Typesense collections with correct schemas and optionally import data';

    // Order matters - dependencies first
    protected $importOrder = [
        'App\Models\Manufacturer',
        'App\Models\Model',
        'App\Models\Province',
        'App\Models\City',
        'App\Models\Vehicle',
    ];

    public function handle()
    {
        $this->info('🔧 Creating Typesense collections...');
        $this->newLine();

        try {
            $config = config('scout.typesense.client-settings');
            $client = new TypesenseClient($config);

            // Get schemas for all models
            $schemas = $this->getCollectionSchemas();
            $totalCollections = count($schemas);
            $currentCollection = 0;

            foreach ($schemas as $collectionName => $schema) {
                $currentCollection++;
                $this->info("[{$currentCollection}/{$totalCollections}] Processing: {$collectionName}");

                try {
                    // Check if collection exists
                    $collection = $client->collections[$collectionName]->retrieve();

                    if ($this->option('force')) {
                        $this->line("  🗑️  Deleting existing collection...");
                        $client->collections[$collectionName]->delete();
                        $this->info("  ✓ Deleted");

                        // Create new collection
                        $this->createCollection($client, $schema);
                    } else {
                        $this->warn("  ⚠️  Collection already exists. Use --force to recreate.");
                    }

                } catch (\Typesense\Exceptions\ObjectNotFound $e) {
                    // Collection doesn't exist, create it
                    $this->createCollection($client, $schema);
                }
            }

            $this->newLine();
            $this->info('✨ All collections created successfully!');

            // Import data if flag is set
            if ($this->option('import')) {
                $this->newLine();
                $this->info('📦 Importing data...');
                $this->newLine();
                $this->importAllData();
            } else {
                $this->newLine();
                $this->info('💡 To import data, run:');
                $this->line('   php artisan typesense:create-collections --force --import');
                $this->line('   OR');
                $this->line('   php artisan typesense:import');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to create collections: ' . $e->getMessage());
            return 1;
        }
    }

    protected function createCollection($client, $schema)
    {
        $this->line("  ⚙️  Creating collection...");

        $startTime = microtime(true);
        $client->collections->create($schema);
        $duration = round((microtime(true) - $startTime) * 1000, 2);

        $fieldCount = count($schema['fields']);
        $this->info("  ✓ Created with {$fieldCount} fields ({$duration}ms)");

        // Show if geo_location field is included
        $hasGeo = collect($schema['fields'])->contains('name', 'geo_location');
        if ($hasGeo) {
            $this->line("    📍 Geo-location enabled for radius filtering");
        }
    }

    protected function importAllData()
    {
        $startTime = microtime(true);
        $totalModels = count($this->importOrder);
        $currentModel = 0;

        foreach ($this->importOrder as $modelClass) {
            $currentModel++;
            $modelName = class_basename($modelClass);

            $this->info("[{$currentModel}/{$totalModels}] Importing {$modelName}...");

            try {
                // Count records first
                $count = $modelClass::count();
                $this->line("  Found {$count} {$modelName} records to import");

                if ($count === 0) {
                    $this->warn("  ⚠️  No records to import");
                    continue;
                }

                // Create progress bar
                $bar = $this->output->createProgressBar($count);
                $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%%');
                $bar->start();

                // Import in chunks with progress updates
                $chunkSize = 500;
                $modelClass::chunk($chunkSize, function ($records) use ($bar, $chunkSize) {
                    $records->searchable();
                    $bar->advance(min($chunkSize, $records->count()));
                });

                $bar->finish();
                $this->newLine();

                $elapsed = round(microtime(true) - $startTime, 2);
                $this->info("  ✅ {$modelName}: {$count} records imported in {$elapsed}s");
                $this->newLine();

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("  ❌ Failed to import {$modelName}: " . $e->getMessage());
            }
        }

        $totalDuration = round(microtime(true) - $startTime, 2);
        $this->newLine();
        $this->info("✨ Import completed in {$totalDuration} seconds");
        $this->info('🔍 Your data is now searchable with InstantSearch!');
    }

    protected function getCollectionSchemas(): array
    {
        $allSchemas = [];

        // Get ALL model settings from config/scout.php
        $modelSettings = config('scout.typesense.model-settings');

        if (empty($modelSettings)) {
            $this->warn('No model settings found in config/scout.php');
            return [];
        }

        // Preserve order from $importOrder
        foreach ($this->importOrder as $modelClass) {
            if (isset($modelSettings[$modelClass]['collection-schema'])) {
                $schema = $modelSettings[$modelClass]['collection-schema'];
                $collectionName = $schema['name'] ?? null;

                if ($collectionName) {
                    $allSchemas[$collectionName] = $schema;
                }
            }
        }

        // Add any remaining schemas not in importOrder
        foreach ($modelSettings as $modelClass => $settings) {
            if (!in_array($modelClass, $this->importOrder) && isset($settings['collection-schema'])) {
                $schema = $settings['collection-schema'];
                $collectionName = $schema['name'] ?? null;

                if ($collectionName && !isset($allSchemas[$collectionName])) {
                    $allSchemas[$collectionName] = $schema;
                }
            }
        }

        return $allSchemas;
    }
}
