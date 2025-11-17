<?php
// app/Console/Commands/CreateTypesenseCollections.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client as TypesenseClient;

class CreateTypesenseCollections extends Command
{
    protected $signature = 'typesense:create-collections {--force : Delete existing collections}';
    protected $description = 'Manually create Typesense collections with correct schemas';

    public function handle()
    {
        $this->info('Creating Typesense collections...');
        $this->newLine();

        try {
            // 🎯 FIX: Manually instantiate client instead of using app() helper
            $config = config('scout.typesense.client-settings');
            $client = new TypesenseClient($config); // <-- CHANGE IS HERE

            // Define schemas for all models
            $schemas = $this->getCollectionSchemas();

            foreach ($schemas as $collectionName => $schema) {
                $this->info("Processing collection: {$collectionName}");

                try {
                    // Check if collection exists
                    $collection = $client->collections[$collectionName]->retrieve();

                    if ($this->option('force')) {
                        $this->warn("  Deleting existing collection...");
                        $client->collections[$collectionName]->delete();
                        $this->info("  ✓ Deleted");

                        // Create new collection
                        $this->createCollection($client, $schema);
                    } else {
                        $this->warn("  Collection already exists. Use --force to recreate.");
                    }

                } catch (\Typesense\Exceptions\ObjectNotFound $e) {
                    // Collection doesn't exist, create it
                    $this->createCollection($client, $schema);
                }
            }

            $this->newLine();
            $this->info('✨ All collections created successfully!');
            $this->info('Now run: php artisan typesense:import');

            return 0;

        } catch (\Exception $e) {
            $this->error('Failed to create collections: ' . $e->getMessage());
            return 1;
        }
    }

    protected function createCollection($client, $schema)
    {
        $this->info("  Creating collection...");
        $client->collections->create($schema);
        $this->info("  ✓ Created with " . count($schema['fields']) . " fields");
    }

    protected function getCollectionSchemas(): array
    {
        $allSchemas = [];

        // 🎯 Get ALL model settings from the 'model-settings' array in config/scout.php
        $modelSettings = config('scout.typesense.model-settings');

        if (empty($modelSettings)) {
            return [];
        }

        foreach ($modelSettings as $modelClass => $settings) {
            // The key 'collection-schema' holds the full schema definition
            if (isset($settings['collection-schema'])) {
                $schema = $settings['collection-schema'];
                $collectionName = $schema['name'] ?? null;

                if ($collectionName) {
                    $allSchemas[$collectionName] = $schema;
                }
            }
        }

        return $allSchemas;
    }
}
