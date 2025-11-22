<?php // app/Http/Controllers/VehicleSearchController.php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Models\FuelType;
use App\Models\MainCategory;
use App\Models\Subcategory;
use App\Models\VehicleType;
use App\Models\City;

class VehicleSearchController extends Controller
{
    /**
     * API endpoint for InstantSearch.js
     */
    public function instantSearch(Request $request)
    {
        // Variable Setup for Search
        $query = $request->input('q', '');
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 12;
        $originCityId = $request->input('origin_city_id');
        $rangeKm = $request->input('range_km');

        // Vehicle Item Card Distance Variables
        $originCity = null; // Initialize
        $lat = null;
        $lon = null;

        try {
            // Build Scout query
            $builder = Vehicle::search($query);

            // 🔑 STEP 1: Get Origin City and Apply Typesense native geo-filtering
            if ($originCityId && $rangeKm && (int)$originCityId > 0 && (float)$rangeKm > 0) {
                // Get origin city coordinates
                $originCity = City::find((int)$originCityId);

                if ($originCity && $originCity->latitude && $originCity->longitude) {
                    $lat = (float) $originCity->latitude;
                    $lon = (float) $originCity->longitude;
                    $radiusKm = (float) $rangeKm;

                    \Log::debug('Geo-Filter: Using Typesense native filtering', [
                        'origin_city_id' => $originCityId,
                        'lat' => $lat,
                        'lon' => $lon,
                        'range_km' => $radiusKm,
                    ]);

                    // Use Typesense's geopoint filtering
                    // Format: geo_location:(lat, lon, radius_km)
                    $builder->options([
                        'filter_by' => "geo_location:($lat, $lon, {$radiusKm} km)"
                    ]);
                }
            }

            // Apply Taxonomy Filters
            if ($request->filled('main_category_id')) {
                $builder->where('main_category_id', (int) $request->input('main_category_id'));
            }
            if ($request->filled('subcategory_id')) {
                $builder->where('subcategory_id', (int) $request->input('subcategory_id'));
            }

            // Apply other filters
            if ($request->filled('manufacturer_id')) {
                $builder->where('manufacturer_id', (int) $request->input('manufacturer_id'));
            }
            if ($request->filled('mileage')) {
                $builder->where('mileage', '<=', (int) $request->input('mileage'));
            }

            // Execute search and paginate
            $results = $builder->paginate($perPage, 'page', $page);
            $vehicleIds = $results->pluck('id')->toArray();

            // Handle case where Scout search returns 0 results
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

            // Hydrate results with Eloquent relationships
            $eloquentQuery = Vehicle::with([
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
            ->select('vehicles.*')
            ->whereIn('vehicles.id', $vehicleIds);

            // 🔑 STEP 2: Calculate distance if origin location is set
            if ($lat !== null && $lon !== null) {
                // Calculate distance from the origin (lat/lon) to the vehicle's city
                // Join to the cities table is required to access latitude/longitude for the calculation
                $distanceCalculation = "
                    ROUND(
                        (
                            ST_DistanceSphere(
                                ST_MakePoint({$lon}, {$lat}),
                                ST_MakePoint(cities.longitude, cities.latitude)
                            ) / 1000.0
                        )::NUMERIC, 0
                    ) AS distance_km
                ";

                $eloquentQuery
                    // Join to the cities table to access the coordinates of the vehicle's location
                    ->join('cities', 'vehicles.city_id', '=', 'cities.id')
                    // Add the calculated distance as a new column named 'distance_km'
                    ->addSelect(\DB::raw($distanceCalculation));
            }

            $vehicles = $eloquentQuery->get()->keyBy('id');

            // Maintain Typesense result order and attach full models
            $hydratedResults = collect($results->items())
                ->map(function($hit) use ($vehicles) {
                    $vehicle = $vehicles->get($hit->id);

                    // 🔑 STEP 3: Ensure distance is attached before rendering
                    // The distance_km attribute is automatically added by ->addSelect
                    if ($vehicle && isset($vehicle->distance_km)) {
                         $vehicle->setAttribute('distance_km', (int)$vehicle->distance_km);
                    }
                    return $vehicle;
                })
                ->filter()
                ->values();

            // Check for authenticated user
            $user = $request->user();

            // Render HTML for each vehicle using the Blade component
            $vehiclesHtml = $hydratedResults->map(function($vehicle) use ($user) {
                $isInWatchlist = $user ? $vehicle->favouredUsers->contains($user) : false;

                // 🔑 STEP 4: Pass the calculated distance to the view component
                // Check for the dynamic attribute added by the query
                $distanceKm = $vehicle->distance_km ?? null;

                return view('components.vehicle-item', [
                    'vehicle' => $vehicle,
                    'isInWatchlist' => $isInWatchlist,
                    'distanceKm' => $distanceKm // Pass the distance
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
            \Log::error('Vehicle search fatal error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'error' => 'Server search failed',
                'message' => 'The server encountered an error processing the search query.',
            ], 500);
        }
    }

    /**
     * Get filter options for refinements
     */
    public function getFilterOptions(Request $request)
    {
        try {
            return response()->json([
                'manufacturers' => \App\Models\Manufacturer::orderBy('name')->get(['id', 'name']),
                'vehicleTypes' => \App\Models\VehicleType::orderBy('name')->get(['id', 'name']),
                'fuelTypes' => FuelType::orderBy('name')->get(['id', 'name']),
                'provinces' => \App\Models\Province::orderBy('name')->get(['id', 'name']),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get cities by province
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
     * Traditional search view
     */
    public function index(Request $request)
    {
        $mainCategories = MainCategory::orderBy('name')->get();
        $fuelTypes = FuelType::orderBy('name')->get();

        return view('vehicle.search', [
            'fuelTypes' => $fuelTypes,
            'mainCategories' => $mainCategories,
        ]);
    }

    /**
     * Get maximum distance to furthest listing
     */
    public function getMaxRange(Request $request, $cityId)
    {
        if (!filter_var($cityId, FILTER_VALIDATE_INT) || $cityId <= 0) {
            return response()->json(['error' => 'Invalid City ID.'], 400);
        }

        try {
            $result = \DB::selectOne("
                SELECT
                    ROUND(CAST(MAX(
                        ST_DistanceSphere(
                            ST_MakePoint(origin_city.longitude, origin_city.latitude),
                            ST_MakePoint(dest_city.longitude, dest_city.latitude)
                        )
                    ) / 1000.0 AS NUMERIC), 2) AS max_distance_km
                FROM
                    cities AS origin_city
                CROSS JOIN
                    cities AS dest_city
                WHERE
                    origin_city.id = :origin_city_id
                    AND dest_city.id IN (SELECT DISTINCT city_id FROM vehicles)
            ", ['origin_city_id' => $cityId]);

            $maxRange = $result ? (float) $result->max_distance_km : 5;

            return response()->json([
                'max_range_km' => max(5.0, $maxRange),
            ]);
        } catch (\Exception $e) {
            \Log::error('PostGIS Max Range Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Failed to calculate max range.'], 500);
        }
    }

    /**
     * Get subcategories by main category
     */
    public function getSubcategoriesByMainCategory(Request $request, $mainCategoryId)
    {
        try {
            $subcategories = Subcategory::where('main_category_id', $mainCategoryId)
                ->orderBy('name')
                ->get(['id', 'name']);
            return response()->json($subcategories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch subcategories: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get vehicle types by subcategory
     */
    public function getVehicleTypesBySubcategory(Request $request, $subcategoryId)
    {
        try {
            $vehicleTypes = VehicleType::where('subcategory_id', $subcategoryId)
                ->orderBy('name')
                ->get(['id', 'name']);
            return response()->json($vehicleTypes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch vehicle types: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get fuel types by subcategory
     */
    public function getFuelTypesBySubcategory(Request $request, $subcategoryId)
    {
        try {
            $subcategory = Subcategory::find($subcategoryId);
            if (!$subcategory) {
                return response()->json([], 404);
            }
            $fuelTypes = $subcategory->availableFuelTypes()->map(fn($ft) => ['id' => $ft->id, 'name' => $ft->name]);
            return response()->json($fuelTypes);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch fuel types: ' . $e->getMessage()], 500);
        }
    }
}
