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
        $provinceId = $request->input('province_id'); // Get the constraint
        $withProvince = $request->boolean('with_province');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // 1. Initialize the search
        $searchQuery = City::search($query);

        // 2. Apply the province constraint if it exists
        // Note: Laravel Scout supports 'where' on indexed fields
        if ($provinceId) {
            $searchQuery->where('province_id', (int) $provinceId);
        }

        $cities = $searchQuery->take(20)->get();

        if ($withProvince) {
            $cities->load('province');
        }

        $mappedCities = $cities
            ->map(fn($city) => [
                'id' => $city->id,
                'name' => $city->name,
                'province_id' => $city->province_id,
                'province' => $city->relationLoaded('province') && $city->province ? [
                    'id' => $city->province->id,
                    'name' => $city->province->name,
                ] : null,
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
