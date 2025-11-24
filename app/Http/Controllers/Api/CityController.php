<?php // app/Http/Controllers/Api/CityController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $withProvince = $request->boolean('with_province'); // Check for the new parameter

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Use Scout search for fuzzy text matching
        $searchQuery = City::search($query);

        // Fetch the results and eagerly load the province relationship
        $cities = $searchQuery
            ->take(20)
            ->get();

        // 🔑 FIX: Hydrate the results with the province relationship if requested
        if ($withProvince) {
            $cities->load('province');
        }

        // 🔑 FIX: Map the results to include the province object
        $mappedCities = $cities
            // Filter out any cities that might be missing a province after hydration (optional)
            ->filter(fn($city) => $city->province)
            ->map(fn($city) => [
                'id' => $city->id,
                'name' => $city->name,
                'province_id' => $city->province_id,
                // CRITICAL: Include the province object
                'province' => [
                    'id' => $city->province->id,
                    'name' => $city->province->name,
                ],
            ]);

        return response()->json($mappedCities);
    }

    public function show(int $id): JsonResponse
    {
        // 🔑 FIX: Eagerly load the province relationship
        $city = City::with('province')->findOrFail($id);

        // 🔑 FIX: Return the province object
        return response()->json([
            'id' => $city->id,
            'name' => $city->name,
            'province_id' => $city->province_id,
            'province' => [
                'id' => $city->province->id,
                'name' => $city->province->name,
            ],
        ]);
    }
}
