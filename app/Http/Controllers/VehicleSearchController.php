<?php

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

            // Apply filters from InstantSearch refinements
            if ($request->filled('manufacturer_id')) {
                $builder->where('manufacturer_id', (int) $request->input('manufacturer_id'));
            }

            if ($request->filled('model_id')) {
                $builder->where('model_id', (int) $request->input('model_id'));
            }

            if ($request->filled('vehicle_type_id')) {
                $builder->where('vehicle_type_id', (int) $request->input('vehicle_type_id'));
            }

            if ($request->filled('fuel_type_id')) {
                $builder->where('fuel_type_id', (int) $request->input('fuel_type_id'));
            }

            if ($request->filled('province_id')) {
                $builder->where('province_id', (int) $request->input('province_id'));
            }

            if ($request->filled('city_id')) {
                $builder->where('city_id', (int) $request->input('city_id'));
            }

            // Price range filter
            if ($request->filled('price_from')) {
                $builder->where('price', '>=', (float) $request->input('price_from'));
            }
            if ($request->filled('price_to')) {
                $builder->where('price', '<=', (float) $request->input('price_to'));
            }

            // Year range filter
            if ($request->filled('year_from')) {
                $builder->where('year', '>=', (int) $request->input('year_from'));
            }
            if ($request->filled('year_to')) {
                $builder->where('year', '<=', (int) $request->input('year_to'));
            }

            // Mileage filter
            if ($request->filled('mileage')) {
                $builder->where('mileage', '<=', (int) $request->input('mileage'));
            }

            // Execute search and paginate
            $results = $builder->paginate($perPage, 'page', $page);

            // Hydrate results with Eloquent to get relationships
            $vehicleIds = $results->pluck('id')->toArray();

            $vehicles = Vehicle::with([
                'city.province',
                'vehicleType',
                'fuelType',
                'manufacturer',
                'model',
                'primaryImage',
                'mainCategory',
                'subcategory',
            ])
            ->whereIn('id', $vehicleIds)
            ->get()
            ->keyBy('id');

            // Maintain Typesense result order and attach full models
            $hydratedResults = collect($results->items())
                ->map(fn($hit) => $vehicles->get($hit->id))
                ->filter()
                ->values();

            return response()->json([
                'hits' => $hydratedResults,
                'nbHits' => $results->total(),
                'page' => $results->currentPage() - 1, // InstantSearch uses 0-based pages
                'nbPages' => $results->lastPage(),
                'hitsPerPage' => $perPage,
                'query' => $query,
            ]);

        } catch (\Exception $e) {
            \Log::error('Vehicle search error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'error' => 'Search failed',
                'message' => $e->getMessage(),
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
