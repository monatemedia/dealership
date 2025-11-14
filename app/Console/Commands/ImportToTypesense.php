<?php // app/Console/Commands/ImportToTypesense.php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportToTypesense extends Command
{
    protected $signature = 'typesense:import {--model= : Specific model to import}';
    protected $description = 'Import data to Typesense with correct IDs';

    protected $modelsToImport = [
        'App\Models\Manufacturer',
        'App\Models\Model',
        'App\Models\Province',
        'App\Models\City',
    ];

    public function handle()
    {
        $models = $this->option('model')
            ? [$this->option('model')]
            : $this->modelsToImport;

        foreach ($models as $modelClass) {
            $this->importModel($modelClass);
        }

        return 0;
    }

    protected function importModel(string $modelClass): void
    {
        $modelName = class_basename($modelClass);
        $this->info("Importing {$modelName}...");

        $model = new $modelClass;
        $count = 0;

        // Import in chunks, but index each record individually
        $model::chunk(100, function ($records) use (&$count, $modelName) {
            foreach ($records as $record) {
                $record->searchable();
                $count++;

                if ($count % 500 === 0) {
                    $this->info("Imported {$count} {$modelName} records...");
                }
            }
        });

        $this->info("✓ {$modelName}: {$count} records imported successfully");
    }
}
