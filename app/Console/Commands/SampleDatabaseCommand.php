<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SampleDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sample';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays the first 3 records, fetching all columns, from key database tables (vehicles, cities, manufacturers, models, users).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database sampling...');

        // Use a simpler array since we are now selecting all columns (*)
        $queries = [
            'vehicles' => [
                'note' => 'Core Vehicle Listings Table (Showing all columns to identify coordinates)',
            ],
            'cities' => [
                'note' => 'Location/City Data Table',
            ],
            'manufacturers' => [
                'note' => 'Manufacturer (Taxonomy) Table',
            ],
            'models' => [
                'note' => 'Model (Taxonomy) Table',
            ],
            'users' => [
                'note' => 'Users/Owners Table',
            ],
        ];

        foreach ($queries as $table => $config) {
            $this->newLine();
            $this->line("--- {$config['note']} ---");
            $this->comment("Table: {$table} (LIMIT 3)");

            try {
                // Fetch all columns using SELECT *
                $results = DB::select("SELECT * FROM {$table} LIMIT 3");

                if (empty($results)) {
                    $this->warn("No records found in '{$table}'.");
                    continue;
                }

                // Convert results to an array of objects
                $data = array_map(function ($item) {
                    return (array) $item;
                }, $results);

                // Get the headers from the first result's keys
                $headers = array_keys($data[0]);

                // Print the result as a table
                $this->table($headers, $data);

            } catch (\Exception $e) {
                // Catch common errors like missing tables
                $this->error("Error querying table '{$table}': " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('Database sampling complete.');
        $this->newLine();
        return Command::SUCCESS;
    }
}
