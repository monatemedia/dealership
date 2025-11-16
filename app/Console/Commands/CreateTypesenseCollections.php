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
            $client = app(TypesenseClient::class);

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
        return [
            'vehicles' => [
                'name' => 'vehicles',
                'fields' => [
                    // DO NOT include 'id' - Scout handles this automatically
                    [
                        'name' => 'title',
                        'type' => 'string',
                        'facet' => false,
                    ],
                    [
                        'name' => 'description',
                        'type' => 'string',
                        'facet' => false,
                        'optional' => true,
                    ],
                    [
                        'name' => 'price',
                        'type' => 'float',
                        'facet' => true,
                    ],
                    [
                        'name' => 'year',
                        'type' => 'int32',
                        'facet' => true,
                    ],
                    [
                        'name' => 'mileage',
                        'type' => 'int32',
                        'facet' => true,
                    ],
                    [
                        'name' => 'status',
                        'type' => 'string',
                        'facet' => true,
                    ],

                    // Manufacturer fields
                    [
                        'name' => 'manufacturer_id',
                        'type' => 'int32',
                        'facet' => true,
                        'optional' => true,
                    ],
                    [
                        'name' => 'manufacturer_name',
                        'type' => 'string',
                        'facet' => true,
                    ],

                    // Model fields
                    [
                        'name' => 'model_id',
                        'type' => 'int32',
                        'facet' => true,
                        'optional' => true,
                    ],
                    [
                        'name' => 'model_name',
                        'type' => 'string',
                        'facet' => true,
                    ],

                    // Vehicle Type fields
                    [
                        'name' => 'vehicle_type_id',
                        'type' => 'int32',
                        'facet' => true,
                        'optional' => true,
                    ],
                    [
                        'name' => 'vehicle_type_name',
                        'type' => 'string',
                        'facet' => true,
                    ],

                    // Fuel Type fields
                    [
                        'name' => 'fuel_type_id',
                        'type' => 'int32',
                        'facet' => true,
                        'optional' => true,
                    ],
                    [
                        'name' => 'fuel_type_name',
                        'type' => 'string',
                        'facet' => true,
                    ],

                    // Location fields
                    [
                        'name' => 'city_id',
                        'type' => 'int32',
                        'facet' => true,
                        'optional' => true,
                    ],
                    [
                        'name' => 'city_name',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'province_id',
                        'type' => 'int32',
                        'facet' => true,
                        'optional' => true,
                    ],
                    [
                        'name' => 'province_name',
                        'type' => 'string',
                        'facet' => true,
                    ],

                    // Timestamps
                    [
                        'name' => 'created_at',
                        'type' => 'int64',
                        'facet' => false,
                    ],
                    [
                        'name' => 'updated_at',
                        'type' => 'int64',
                        'facet' => false,
                    ],
                ],
                'default_sorting_field' => 'created_at',
            ],

            'manufacturers' => [
                'name' => 'manufacturers',
                'fields' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'slug',
                        'type' => 'string',
                        'facet' => false,
                    ],
                    [
                        'name' => 'created_at',
                        'type' => 'int64',
                        'facet' => false,
                    ],
                ],
                'default_sorting_field' => 'created_at',
            ],

            'models' => [
                'name' => 'models',
                'fields' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'slug',
                        'type' => 'string',
                        'facet' => false,
                    ],
                    [
                        'name' => 'manufacturer_id',
                        'type' => 'int32',
                        'facet' => true,
                    ],
                    [
                        'name' => 'created_at',
                        'type' => 'int64',
                        'facet' => false,
                    ],
                ],
                'default_sorting_field' => 'created_at',
            ],

            'provinces' => [
                'name' => 'provinces',
                'fields' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'slug',
                        'type' => 'string',
                        'facet' => false,
                    ],
                    [
                        'name' => 'created_at',
                        'type' => 'int64',
                        'facet' => false,
                    ],
                ],
                'default_sorting_field' => 'created_at',
            ],

            'cities' => [
                'name' => 'cities',
                'fields' => [
                    [
                        'name' => 'name',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'slug',
                        'type' => 'string',
                        'facet' => false,
                    ],
                    [
                        'name' => 'province_id',
                        'type' => 'int32',
                        'facet' => true,
                    ],
                    [
                        'name' => 'created_at',
                        'type' => 'int64',
                        'facet' => false,
                    ],
                ],
                'default_sorting_field' => 'created_at',
            ],
        ];
    }
}
