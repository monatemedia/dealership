<?php // database/seeders/SouthAfricanCitySeeder.php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SouthAfricanCitySeeder extends Seeder
{
    private const JSON_FILE_PATH = 'database/sources/SouthAfricanCities.json';
    private const GITHUB_URL = 'https://raw.githubusercontent.com/dirkstrauss/SouthAfrica/master/SouthAfricanCities.json';

    public function run(): void
    {
        $this->command->info("Seeding South African provinces and cities...");

        // Step 1: Get the JSON data
        $jsonData = $this->getJsonData();
        if (!$jsonData) {
            $this->command->error("Failed to load city data.");
            return;
        }

        // Step 2: Import provinces using upsert
        $this->command->info("Importing provinces...");
        $this->importProvinces($jsonData);

        // Step 3: Build province lookup map
        $provinceMap = Province::pluck('id', 'name')->all();

        // Step 4: Import cities using upsert
        $this->command->info("Importing cities (~13,000 records)...");
        $this->importCities($jsonData, $provinceMap);

        $this->command->info("Finished seeding provinces and cities.");
    }

    private function getJsonData(): ?array
    {
        $localPath = base_path(self::JSON_FILE_PATH);
        // Check if file exists locally
        if (File::exists($localPath)) {
            $this->command->info("Loading cities from local file...");
            $content = File::get($localPath);
            return json_decode($content, true);
        }

        // Download from GitHub
        $this->command->info("Local file not found. Downloading from GitHub...");
        try {
            $response = Http::timeout(60)->get(self::GITHUB_URL);
            if ($response->successful()) {
                // Ensure the directory exists
                $directory = dirname($localPath);
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                // Save the file locally for future use
                File::put($localPath, $response->body());
                $this->command->info("File downloaded and saved to: " . self::JSON_FILE_PATH);
                return json_decode($response->body(), true);
            }
            $this->command->error("Failed to download file. Status: " . $response->status());
            return null;
        } catch (\Exception $e) {
            $this->command->error("Error downloading file: " . $e->getMessage());
            return null;
        }
    }

    private function importProvinces(array $data): void
    {
        $provinces = collect($data)
            ->pluck('ProvinceName')
            ->unique()
            ->filter()
            ->map(fn($name) => [
                'name' => $name,
            ])
            ->values()
            ->all();

        // Use upsert for idempotent operation. Using 'name' in the update array
        // forces PostgreSQL to use ON CONFLICT DO UPDATE SET name = EXCLUDED.name,
        // which reliably avoids the duplicate key error when no real update is needed
        // (as confirmed in the previous step, since the models don't use timestamps).
        DB::table('provinces')->upsert(
            $provinces,
            ['name'], // Unique key
            ['name'] // Update an existing field to itself if conflict occurs
        );

        $this->command->info("Processed " . count($provinces) . " provinces.");
    }

    private function importCities(array $data, array $provinceMap): void
    {
        $chunkSize = 1000; // Larger chunks for better performance
        $chunks = array_chunk($data, $chunkSize);
        $totalProcessed = 0;

        foreach ($chunks as $chunk) {
            $citiesToUpsert = [];
            foreach ($chunk as $city) {
                $provinceId = $provinceMap[$city['ProvinceName']] ?? null;
                if ($provinceId && !empty($city['AccentCity'])) {
                    $citiesToUpsert[] = [
                        'name' => $city['AccentCity'],
                        'province_id' => $provinceId,
                        'latitude' => $city['Latitude'] ?? null,
                        'longitude' => $city['Longitude'] ?? null,
                    ];
                }
            }

            if (!empty($citiesToUpsert)) {
                // **CRITICAL FIX: Deduplicate the chunk before upserting**
                // This prevents the PostgreSQL 'Cardinality violation' error
                // when the source JSON contains multiple entries for the same city/province.
                $citiesToUpsert = collect($citiesToUpsert)
                    ->unique(fn ($item) => $item['name'] . '|' . $item['province_id'])
                    ->values()
                    ->all();

                // Use upsert for idempotent operation
                DB::table('cities')->upsert(
                    $citiesToUpsert,
                    ['name', 'province_id'], // Composite unique key
                    ['latitude', 'longitude'] // Update these if exists
                );
                $totalProcessed += count($citiesToUpsert); // Use the count of the unique items
            }
            unset($citiesToUpsert);
            gc_collect_cycles();

            // Show progress less frequently
            if ($totalProcessed % 3000 === 0 || $totalProcessed >= count($data)) {
                $this->command->info("Processed {$totalProcessed} unique cities...");
            }
        }
        $this->command->info("Imported {$totalProcessed} unique cities.");
    }
}
