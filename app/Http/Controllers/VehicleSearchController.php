<?php // app/Http/Controllers/VehicleSearchController.php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleSearchController extends Controller
{
    /**
     * API endpoint for InstantSearch.js
     * This endpoint handles the search and returns results compatible with InstantSearch
     */
    public function instantSearch(Request $request)
    {
        $query = $request->input('q', '');
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 12;

        try {
            // Build Scout query
            $builder = Vehicle::search($query);

            // Apply Taxonomy Filters
            if ($request->filled('main_category_id')) {
                $builder->where('main_category_id', (int) $request->input('main_category_id'));
            }
            if ($request->filled('subcategory_id')) {
                $builder->where('subcategory_id', (int) $request->input('subcategory_id'));
            }

            // Apply other filters...
            if ($request->filled('manufacturer_id')) {
                $builder->where('manufacturer_id', (int) $request->input('manufacturer_id'));
            }
            // ... (keep all other existing filter applications) ...
            if ($request->filled('mileage')) {
                $builder->where('mileage', '<=', (int) $request->input('mileage'));
            }

            // Execute search and paginate
            $results = $builder->paginate($perPage, 'page', $page);

            $vehicleIds = $results->pluck('id')->toArray();

            // CRITICAL FIX 1: Handle case where Scout search returns 0 results
            if (empty($vehicleIds)) {
                 return response()->json([
                    'hits' => [],
                    'nbHits' => 0,
                    'page' => $results->currentPage() - 1,
                    'nbPages' => 0,
                    'hitsPerPage' => $perPage,
                    'query' => $query,
                ]);
            }

            // Hydrate results with Eloquent to get relationships
            $vehicles = Vehicle::with([
                'city.province',
                'vehicleType',
                'fuelType',
                'manufacturer',
                'model',
                'primaryImage',
                'mainCategory',
                'subcategory',
                'favouredUsers'
            ])
            ->whereIn('id', $vehicleIds)
            ->get()
            ->keyBy('id');

            // Maintain Typesense result order and attach full models
            $hydratedResults = collect($results->items())
                ->map(fn($hit) => $vehicles->get($hit->id))
                ->filter()
                ->values();

            // CRITICAL FIX 2: Check for authenticated user outside the loop
            $user = $request->user();

            // Render HTML for each vehicle using the Blade component
            $vehiclesHtml = $hydratedResults->map(function($vehicle) use ($user) {
                // Safely determine watchlist status for guests
                $isInWatchlist = $user ? $vehicle->favouredUsers->contains($user) : false;

                return view('components.vehicle-item', [
                    'vehicle' => $vehicle,
                    'isInWatchlist' => $isInWatchlist
                ])->render();
            })->toArray();

            return response()->json([
                'hits' => $vehiclesHtml,
                'nbHits' => $results->total(),
                'page' => $results->currentPage() - 1,
                'nbPages' => $results->lastPage(),
                'hitsPerPage' => $perPage,
                'query' => $query,
            ]);
        } catch (\Exception $e) {
            // Log the detailed error for server inspection
            \Log::error('Vehicle search fatal error: ' . $e->getMessage(), ['exception' => $e]);

            // Return a clearer 500 response message
            return response()->json([
                'error' => 'Server search failed',
                'message' => 'The server encountered an error processing the search query.',
            ], 500);
        }
    }

    /**
     * Get filter options for refinements (manufacturers, models, etc.)
     */
    public function getFilterOptions(Request $request)
    {
        try {
            return response()->json([
                'manufacturers' => \App\Models\Manufacturer::orderBy('name')->get(['id', 'name']),
                'vehicleTypes' => \App\Models\VehicleType::orderBy('name')->get(['id', 'name']),
                'fuelTypes' => \App\Models\FuelType::orderBy('name')->get(['id', 'name']),
                'provinces' => \App\Models\Province::orderBy('name')->get(['id', 'name']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get cities by province (for cascading filter)
     */
    public function getCitiesByProvince(Request $request, $provinceId)
    {
        try {
            $cities = \App\Models\City::where('province_id', $provinceId)
                ->orderBy('name')
                ->get(['id', 'name']);
            return response()->json($cities);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Traditional search view (initial page load)
     */
    public function index(Request $request)
    {
        return view('vehicle.search');
    }
}
