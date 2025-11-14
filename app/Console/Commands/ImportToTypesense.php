<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Typesense\Client as TypesenseClient; // Assuming you have a way to resolve the Typesense Client

class ImportToTypesense extends Command
{
    protected $signature = 'typesense:import {--model= : Specific model to import} {--force : Force a fresh import (drop and create collection)}';
    protected $description = 'Import data to Typesense with correct IDs and improved performance.';
    protected $modelsToImport = [
        'App\Models\Manufacturer',
        'App\Models\Model',
        'App\Models\Province',
        'App\Models\City',
    ];

    // Increase chunk size for fewer database queries and bulk indexing operations
    protected const CHUNK_SIZE = 2000;

    public function handle()
    {
        $models = $this->option('model')
            ? [$this->option('model')]
            : $this->modelsToImport;

        // Optionally, resolve the Typesense client if you need it for fresh import/schema
        // $typesenseClient = app(TypesenseClient::class);

        foreach ($models as $modelClass) {
            // Check if the model uses the Searchable trait
            if (!in_array(\Laravel\Scout\Searchable::class, class_uses($modelClass))) {
                $this->error("Model {$modelClass} does not use the Laravel Scout Searchable trait.");
                continue;
            }
            $this->importModel($modelClass);
        }

        return 0;
    }

    protected function importModel(string $modelClass): void
    {
        $modelName = class_basename($modelClass);
        $this->info("Importing {$modelName}...");

        $count = 0;
        $query = $modelClass::query();

        // Optional: Optimize Database Query
        if (method_exists($modelClass, 'getScoutImportColumns')) {
            $query->select((new $modelClass)->getScoutImportColumns());
        }

        // Use the query builder's chunk method
        $query->chunk(self::CHUNK_SIZE, function ($records) use (&$count, $modelName) {

            // Revert to the standard searchable() method.
            // The Typesense Scout driver converts this into an optimized bulk operation.
            $records->searchable();

            $count += $records->count();

            if ($count % self::CHUNK_SIZE === 0) {
                $this->info("Imported {$count} {$modelName} records...");
            }
        });

        $this->info("✅ {$modelName}: {$count} records imported successfully");
    }
}
