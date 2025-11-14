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
        $provinceId = $request->input('province_id');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $searchQuery = City::search($query);

        // Filter by province if provided
        if ($provinceId) {
            $searchQuery->where('province_id', $provinceId);
        }

        $cities = $searchQuery
            ->take(20)
            ->get()
            ->map(fn($city) => [
                'id' => $city->id,
                'name' => $city->name,
                'province_id' => $city->province_id,
            ]);

        return response()->json($cities);
    }

    public function show(int $id): JsonResponse
    {
        $city = City::findOrFail($id);

        return response()->json([
            'id' => $city->id,
            'name' => $city->name,
            'province_id' => $city->province_id,
        ]);
    }
}
