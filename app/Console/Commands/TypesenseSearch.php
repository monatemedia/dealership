<?php // app/Console/Commands/TypesenseSearch.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;

class TypesenseSearch extends Command
{
    protected $signature = 'typesense:search
        {query : Search query}
        {--limit=10 : Number of results to return}';

    protected $description = 'Test Typesense search from the command line';

    public function handle()
    {
        $query = $this->argument('query');
        $limit = $this->option('limit');

        $this->info("🔍 Searching for: \"{$query}\"");
        $this->newLine();

        try {
            // Get search results
            $searchResults = Vehicle::search($query)->take($limit)->get();

            if ($searchResults->isEmpty()) {
                $this->warn('No results found.');
                return 0;
            }

            $this->info("Found {$searchResults->count()} results:");
            $this->newLine();

            // Load relationships for display
            $vehicles = Vehicle::with(['city.province', 'manufacturer', 'model'])
                ->whereIn('id', $searchResults->pluck('id'))
                ->get()
                ->keyBy('id');

            $headers = ['ID', 'Title', 'Price', 'Year', 'Location'];
            $rows = [];

            foreach ($searchResults as $result) {
                $vehicle = $vehicles->get($result->id);

                if ($vehicle) {
                    $rows[] = [
                        $vehicle->id,
                        \Illuminate\Support\Str::limit($vehicle->title ?? 'N/A', 50),
                        'R ' . number_format($vehicle->price),
                        $vehicle->year,
                        $vehicle->city?->name ?? 'N/A',
                    ];
                }
            }

            $this->table($headers, $rows);

        } catch (\Exception $e) {
            $this->error('Search failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
