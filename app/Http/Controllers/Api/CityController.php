<?php // app/Http/Controllers/CityController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $provinceId = $request->input('province_id');

        $cities = City::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->when($provinceId, function ($q) use ($provinceId) {
                $q->where('province_id', $provinceId);
            })
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'province_id']);

        return response()->json($cities);
    }

    public function show($id)
    {
        $city = City::findOrFail($id);

        return response()->json([
            'id' => $city->id,
            'name' => $city->name,
            'province_id' => $city->province_id,
        ]);
    }
}
