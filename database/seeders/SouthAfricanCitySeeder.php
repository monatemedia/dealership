<?php

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
        $this->command->info("    Seeding South African provinces and cities...");

        // Step 1: Get the JSON data
        $jsonData = $this->getJsonData();

        if (!$jsonData) {
            $this->command->error("    Failed to load city data.");
            return;
        }

        // Step 2: Import provinces first
        $this->command->info("    Importing provinces...");
        $this->importProvinces($jsonData);

        // Step 3: Build province lookup map
        $provinceMap = Province::pluck('id', 'name')->all();

        // Step 4: Import cities in chunks
        $this->command->info("    Importing cities (this may take a while - ~13,000 records)...");
        $this->importCities($jsonData, $provinceMap);

        $this->command->info("      Finished seeding provinces and cities.");
    }

    private function getJsonData(): ?array
    {
        $localPath = base_path(self::JSON_FILE_PATH);

        // Check if file exists locally
        if (File::exists($localPath)) {
            $this->command->info("    Loading cities from local file...");
            $content = File::get($localPath);
            return json_decode($content, true);
        }

        // Download from GitHub
        $this->command->info("    Local file not found. Downloading from GitHub...");

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
                $this->command->info("    File downloaded and saved to: " . self::JSON_FILE_PATH);

                return json_decode($response->body(), true);
            }

            $this->command->error("    Failed to download file. Status: " . $response->status());
            return null;
        } catch (\Exception $e) {
            $this->command->error("    Error downloading file: " . $e->getMessage());
            return null;
        }
    }

    private function importProvinces(array $data): void
    {
        $provinces = collect($data)
            ->pluck('ProvinceName')
            ->unique()
            ->filter()
            ->values();

        foreach ($provinces as $provinceName) {
            Province::firstOrCreate(['name' => $provinceName]);
        }

        $this->command->info("      Imported " . $provinces->count() . " provinces.");
    }

    private function importCities(array $data, array $provinceMap): void
    {
        // Process in chunks to manage memory
        $chunks = array_chunk($data, 500);
        $totalProcessed = 0;

        foreach ($chunks as $chunk) {
            $citiesToInsert = [];

            foreach ($chunk as $city) {
                $provinceId = $provinceMap[$city['ProvinceName']] ?? null;

                if ($provinceId && !empty($city['AccentCity'])) {
                    $citiesToInsert[] = [
                        'name' => $city['AccentCity'],
                        'province_id' => $provinceId,
                        'latitude' => $city['Latitude'] ?? null,
                        'longitude' => $city['Longitude'] ?? null,
                        // 'created_at' => now(),
                        // 'updated_at' => now(),
                    ];
                }
            }

            if (!empty($citiesToInsert)) {
                DB::table('cities')->insertOrIgnore($citiesToInsert);
                $totalProcessed += count($citiesToInsert);
            }

            // Force garbage collection for memory management
            unset($citiesToInsert);
            gc_collect_cycles();

            // Show progress
            $this->command->info("      Processed {$totalProcessed} cities...");
        }

        $this->command->info("      Imported {$totalProcessed} cities.");
    }
}
